<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\InvalidPayloadException;
use App\Model\Task\CreateTaskPayload;
use App\Model\Task\DeleteTaskPayload;
use App\Model\Task\TaskDTO;
use App\Model\Task\UpdateTaskPayload;
use App\Repository\TaskRepository;
use App\Service\Task\TaskCreator;
use App\Service\Task\TaskDeleter;
use App\Service\Task\TaskUpdater;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/task', name: 'app_task_')]
class TaskController extends AbstractController
{
    #[Route('', name: 'all')]
    public function index(TaskRepository $taskRepository): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json(array_map(static function ($task) {
            return TaskDTO::fromTask($task);
        }, $taskRepository->findByUser($user)));
    }

    #[Route('/create', name: 'create', methods: 'POST')]
    public function create(TaskCreator $taskCreator, Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        try {
            $payload = array_merge([
                'ownerId' => $user->getId()?->toRfc4122(),
            ], json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (\JsonException $e) {
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

    #[Route('/delete', name: 'delete_bulk', methods: 'DELETE')]
    public function deleteBulk(Request $request, TaskDeleter $taskDeleter): JsonResponse
    {
        // todo Add check if user is eligible to delete task
        try {
            $ids = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        array_walk($ids, static fn($id) => $taskDeleter(DeleteTaskPayload::from(['id' => $id])));

        return $this->json([]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: 'DELETE')]
    public function delete(string $id, TaskDeleter $taskDeleter): JsonResponse
    {
        // todo Add check if user is eligible to delete task

        $taskDeleter(DeleteTaskPayload::from(['id' => $id]));

        return $this->json([]);
    }

    #[Route('/update/{id}', name: 'update', methods: 'POST')]
    public function update(string $id, TaskUpdater $taskUpdater, Request $request): JsonResponse
    {
        try {
            $payload = array_merge([
                'id' => $id,
            ], json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (\JsonException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $task = $taskUpdater(UpdateTaskPayload::from($payload));

        return $this->json(
            TaskDTO::fromTask($task)
        );
    }

    #[Route('/{id}', name: 'show')]
    public function show(string $id, TaskRepository $taskRepository): Response
    {
        if (!$task = $taskRepository->find($id)) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        return $this->json(TaskDTO::fromTask($task));
    }
}
