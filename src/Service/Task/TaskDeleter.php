<?php

declare(strict_types=1);

namespace App\Service\Task;

use App\Entity\Task;
use App\Model\DeleteTaskPayload;
use App\Repository\TaskRepository;

class TaskDeleter
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
    ) {
    }

    public function __invoke(DeleteTaskPayload $payload): void
    {
        if (!$task = $this->taskRepository->find($payload->getId())) {
            throw new \RuntimeException('Task not found');
        }

        if ($timer = $task->getTimer()) {
            $timer->setTask(null);
        }

        $this->taskRepository->remove($task, true);
    }
}
