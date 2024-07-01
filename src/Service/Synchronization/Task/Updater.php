<?php

declare(strict_types=1);

namespace App\Service\Synchronization\Task;

use App\Entity\User;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Model\Task\UpdateTaskPayload;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Service\Synchronization\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'update.task')]
readonly class Updater implements ProcessorInterface
{
    public function __construct(
        private TaskRepository $taskRepository,
        private ProjectRepository $projectRepository,
        private ClientRepository $clientRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function sync(User $user, ActionPayloadInterface $payload): void
    {
        if (!$payload instanceof UpdateTaskPayload) {
            throw new \RuntimeException('Invalid payload');
        }

        if (!$task = $this->taskRepository->findOneById($payload->getTaskId())) {
            throw new \RuntimeException('Task not found');
        }

        if ($this->userRepository->findOneById($task->getOwner()->getId())?->getId()->toRfc4122() !== $payload->getUserId()->toRfc4122()) {
            throw new \RuntimeException('Task does not belong to the user');
        }

        $client = $payload->getClientId() ? $this->clientRepository->find($payload->getClientId()) : null;
        $project = $payload->getProjectId() ? $this->projectRepository->find($payload->getProjectId()) : null;
        $task
            ->setName($payload->getName())
            ->setDescription($payload->getDescription())
            ->setClient($client)
            ->setProject($project);

        if ($payload->getParentTaskId() && $parentTask = $this->taskRepository->findOneById($payload->getParentTaskId()())) {
            $parentTask->addSubtask($task);
        }
        if($payload->getParentTaskId() === null && $task->getParentTask()) {
            $task->getParentTask()->removeSubtask($task);
        }

        $this->taskRepository->save($task, true);
    }
}
