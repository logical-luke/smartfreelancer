<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\Entity\Client;
use App\Entity\Project;
use App\Entity\Task;
use App\Entity\User;
use App\Exception\InvalidPayloadException;
use App\Model\Client\ClientDTO;
use App\Model\Project\ProjectDTO;
use App\Model\Synchronization\Payload;
use App\Model\Task\TaskDTO;
use App\Model\Timer\TimerDTO;
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
    public function initial(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json([
            'clients' => array_map(static function (Client $client) { return ClientDTO::fromClient($client); }, $user->getClients()->toArray()),
            'projects' => array_map(static function (Project $project) { return ProjectDTO::fromProject($project); }, $user->getProjects()->toArray()),
            'tasks' => array_map(static function (Task $task) { return TaskDTO::fromTask($task); }, $user->getTasks()->toArray()),
            'timer' => $user->getTimer() ? TimerDTO::fromTimer($user->getTimer()) : null,
        ]);
    }

    #[Route('/queue', name: 'collect', methods: "POST")]
    public function collect(Request $request, Producer $producer, ProcessorsCollection $processorsCollection): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user->getId()) {
            return $this->json(['error' => 'User is not authenticated.'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $payload = Payload::fromUserRequest($user, $request);

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

        return $this->json(['uploadTime' => time()]);
    }
}
