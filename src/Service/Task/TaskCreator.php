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
readonly class TaskCreator implements ProcessorInterface
{
    public function __construct(
        private TaskRepository $taskRepository,
        private UserRepository $userRepository,
        private ProjectRepository $projectRepository,
        private ClientRepository $clientRepository,
    ) {
    }

    public function sync(User $user, ActionPayloadInterface $payload): void
    {
        if (!$payload instanceof CreateTaskPayload) {
            throw new \RuntimeException('Invalid payload');
        }

        $task = Task::fromUser($user);

        $task->setName($payload->getName());
        $task->setDescription($payload->getDescription());

        if ($payload->getProjectId() && $project = $this->projectRepository->find($payload->getProjectId())) {
            $task->setProject($project);
        }

        if ($payload->getClientId() && $client = $this->clientRepository->find($payload->getClientId())) {
            $task->setClient($client);
        }

        if ($payload->getTaskId() && $parentTask = $this->taskRepository->find($payload->getTaskId())) {
            $parentTask->addSubtask($task);
        }

        $this->taskRepository->save($task, true);
    }
}
