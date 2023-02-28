<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Model\Time\TimeDTO;
use App\Service\Synchronization\RabbitMQProducer;
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
            'clients' => $user->getClients(),
            'projects' => $user->getProjects(),
            'tasks' => $user->getTasks(),
            'timer' => $user->getTimer(),
        ]);
    }

    #[Route('/queue', name: 'collect', methods: "POST")]
    public function collect(Request $request, RabbitMQProducer $producer): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        try {
            $payload = array_merge(
                [
                    'userId' => $user->getId(),
                ],
                json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR)
            );
        } catch (\JsonException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $producer->publishMessage(json_encode($payload), 'synchronization');

        return $this->json(['uploadTime' => time()]);
    }
}
