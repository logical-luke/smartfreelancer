<?php

declare(strict_types=1);

namespace App\Service\Task;

use App\Entity\Task;
use App\Model\Task\UpdateTaskPayload;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: true)]
class TaskUpdater
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly ProjectRepository $projectRepository,
    ) {
    }

    public function __invoke(UpdateTaskPayload $payload): Task
    {
        if (!$task = $this->taskRepository->find($payload->getId())) {
            throw new \RuntimeException('Task not found');
        }

        // todo Add check if user is eligible to update task
        // if ($task->getOwner()->getId() !==) {
        //
        // }

        $task->setName($payload->getName());
        $task->setDescription($payload->getDescription());
        $task->setProject(null);
        if ($projectId = $payload->getProjectId()) {
            $task->setProject($this->projectRepository->find($projectId));
        }

        $this->taskRepository->flush();

        return $task;
    }
}
