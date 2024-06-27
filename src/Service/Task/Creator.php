<?php

declare(strict_types=1);

namespace App\Service\Task;

use App\Entity\Task;
use App\Entity\User;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Model\Task\CreateTaskPayload;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Service\Synchronization\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;


#[AsTaggedItem(index: 'create.task')]
readonly class Creator implements ProcessorInterface
{
    public function __construct(
        private TaskRepository $taskRepository,
        private ProjectRepository $projectRepository,
        private ClientRepository $clientRepository,
    ) {
    }

    public function sync(User $user, ActionPayloadInterface $payload): void
    {
        if (!$payload instanceof CreateTaskPayload) {
            throw new \RuntimeException('Invalid payload');
        }

        if ($this->taskRepository->findOneById($payload->getTaskId())) {
            throw new \RuntimeException('Task already exists');
        }

        $client = $payload->getClientId() ? $this->clientRepository->find($payload->getClientId()) : null;
        $project = $payload->getProjectId() ? $this->projectRepository->find($payload->getProjectId()) : null;
        $task = (Task::fromUser($user, $payload->getTaskId(), $payload->getName(), $payload->getCreatedAt()))
            ->setDescription($payload->getDescription())
            ->setClient($client)
            ->setProject($project);

        if ($payload->getParentTaskId() && $parentTask = $this->taskRepository->findOneById($payload->getTaskId())) {
            $parentTask->addSubtask($task);
        }

        $this->taskRepository->save($task, true);
    }
}
