<?php

namespace App\Controller;

use App\Exception\InvalidPayloadException;
use App\Model\Task\CreateTaskPayload;
use App\Model\Task\DeleteTaskPayload;
use App\Model\Task\TaskDTO;
use App\Model\Task\UpdateTaskPayload;
use App\Repository\TaskRepository;
use App\Service\Task\TaskCreator;
use App\Service\Task\TaskDeleter;
use App\Service\Task\TaskUpdater;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/task', name: 'app_task_')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'all')]
    public function index(TaskRepository $taskRepository): JsonResponse
    {
        return $this->json(array_map(static function ($task) {
            return (TaskDTO::fromTask($task));
        }, $taskRepository->findByUser($this->getUser())));
    }

    #[Route('/create', name: 'create', methods: 'POST')]
    public function create(TaskCreator $taskCreator, Request $request): JsonResponse
    {
        try {
            $payload = array_merge([
                'ownerId' => $this->getUser()->getId(),
            ], json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (JsonException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        try {
            return $this->json(
                TaskDTO::fromTask(
                    $taskCreator(CreateTaskPayload::from($payload))
                )
            );
        } catch (InvalidPayloadException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/delete/{id}', name: 'delete', methods: 'DELETE')]
    public function delete(int $id, TaskDeleter $taskDeleter): JsonResponse
    {
        // todo Add check if user is eligible to delete task

        $taskDeleter(DeleteTaskPayload::from(['id' => $id]));

        return $this->json([]);
    }

    #[Route('/update/{id}', name: 'update', methods: 'POST')]
    public function update(int $id, TaskUpdater $taskUpdater, Request $request): JsonResponse
    {
        try {
            $payload = array_merge([
                'id' => $id,
            ], json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (JsonException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $task = $taskUpdater(UpdateTaskPayload::from($payload));

        return $this->json(
            TaskDTO::fromTask($task)
        );
    }

    #[Route('/{id}', name: 'show')]
    public function show(int $id, TaskRepository $taskRepository): Response
    {
        if (!$task = $taskRepository->find($id)) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        return $this->json(TaskDTO::fromTask($task));
    }
}
