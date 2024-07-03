<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\Entity\Client;
use App\Entity\Project;
use App\Entity\SynchronizationLog;
use App\Entity\Task;
use App\Entity\User;
use App\Exception\InvalidPayloadException;
use App\Model\Client\ClientDto;
use App\Model\Project\ProjectDTO;
use App\Model\Synchronization\Payload;
use App\Model\Task\TaskDto;
use App\Model\Timer\TimerDTO;
use App\Repository\SynchronizationLogRepository;
use App\Service\Client\ClientDtoArrayMapper;
use App\Service\Synchronization\ProcessorsCollection;
use App\Service\Synchronization\Producer;
use JsonException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/synchronization', name: 'app_synchronization_')]
class SynchronizationController extends AbstractController
{
    #[Route('', name: 'initial', methods: "GET")]
    public function initial(ClientDtoArrayMapper $clientDtoArrayMapper): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json([
            'clients' => $clientDtoArrayMapper->map($user->getClients()),
            'projects' => array_map(static function (Project $project) { return ProjectDTO::fromProject($project); }, $user->getProjects()->toArray()),
            'tasks' => array_map(static function (Task $task) { return TaskDto::fromTask($task); }, $user->getTasks()->toArray()),
            'timer' => $user->getTimer() ? TimerDTO::fromTimer($user->getTimer()) : null,
        ]);
    }

    #[Route('/queue', name: 'collect', methods: "POST")]
    public function collect(Request $request, Producer $producer, ProcessorsCollection $processorsCollection, SynchronizationLogRepository $synchronizationLogRepository): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user->getId()) {
            return $this->json(['error' => 'User is not authenticated.'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $payload = Payload::fromUserRequest($user, $request);

            $synchronizationLog = SynchronizationLog::fromPayload($user, $payload);

            if (null !== $synchronizationLogRepository->findOneById($synchronizationLog->getId())) {
                return $this->json(['error' => 'Synchronization log already exists'], Response::HTTP_CONFLICT);
            }

            $synchronizationLogRepository->save($synchronizationLog, true);

            if (!$processorsCollection->exist($payload->getProcessorKey())) {
                return $this->json(['error' => 'Invalid action for the resource'], Response::HTTP_BAD_REQUEST);
            }
        } catch (RuntimeException|JsonException|InvalidPayloadException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        try {
            $producer->publishMessage(json_encode($payload, JSON_THROW_ON_ERROR), 'synchronization');
        } catch (JsonException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json(['id' => $synchronizationLog->getId()->toRfc4122()]);
    }
}
