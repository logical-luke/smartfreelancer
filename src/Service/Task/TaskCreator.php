<?php

declare(strict_types=1);

namespace App\Service\Task;

use App\Entity\Task;
use App\Model\Task\CreateTaskPayload;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: true)]
class TaskCreator
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly UserRepository $userRepository,
        private readonly ProjectRepository $projectRepository,
    ) {
    }

    public function __invoke(CreateTaskPayload $payload): Task
    {
        if (!$user = $this->userRepository->find($payload->getOwnerId())) {
            throw new \RuntimeException('User not found');
        }

        $task = Task::fromUser($user);

        $task->setName($payload->getName());
        $task->setDescription($payload->getDescription());

        if ($payload->getProjectId() && $project = $this->projectRepository->find($payload->getProjectId())) {
            $task->setProject($project);
        }

        $this->taskRepository->save($task, true);

        return $task;
    }
}
