<?php

declare(strict_types=1);

namespace App\Service\Task;

use App\Entity\Task;
use App\Entity\User;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Model\Task\DeleteTaskPayload;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Service\Synchronization\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'delete.task')]
readonly class Deleter implements ProcessorInterface
{
    public function __construct(
        private TaskRepository $taskRepository,
        private UserRepository $userRepository,
    ) {
    }


    public function sync(User $user, ActionPayloadInterface $payload): void
    {
        if (!$payload instanceof DeleteTaskPayload) {
            throw new \RuntimeException('Invalid payload');
        }

        if (!$task = $this->taskRepository->find($payload->getTaskId())) {
            throw new \RuntimeException('Task not found');
        }

        if ($this->userRepository->findOneById($task->getOwner()->getId())?->getId()->toRfc4122() !== $payload->getUserId()->toRfc4122()) {
            throw new \RuntimeException('Task does not belong to the user');
        }

        $this->removeTaskRelatedData($task);

        $subtasks = $task->getSubtasks();
        foreach ($subtasks as $subtask) {
            $task->removeSubtask($subtask);
            $this->removeTaskRelatedData($subtask);
            $this->taskRepository->remove($subtask);
        }

        $this->taskRepository->remove($task, true);
    }

    private function removeTaskRelatedData(Task $task): void
    {
        if ($timer = $task->getTimer()) {
            $timer->setTask(null);
        }

        foreach ($task->getTimeEntries() as $timeEntry) {
            $timeEntry->setTask(null);
        }
    }
}
