<?php

declare(strict_types=1);

namespace App\Service\Task;

use App\Model\Task\DeleteTaskPayload;
use App\Repository\TaskRepository;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: true)]
class TaskDeleter
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
    ) {
    }

    public function __invoke(DeleteTaskPayload $payload): void
    {
        if (!$task = $this->taskRepository->find($payload->getTaskId())) {
            throw new \RuntimeException('Task not found');
        }

        if ($timer = $task->getTimer()) {
            $timer->setTask(null);
        }

        foreach ($task->getTimeEntries() as $timeEntry) {
            $timeEntry->setTask(null);
        }

        $this->taskRepository->remove($task, true);
    }
}
