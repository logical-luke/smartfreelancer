<?php

declare(strict_types=1);

namespace App\Service\Timer;

use App\Entity\Timer;
use App\Entity\User;
use App\Exception\InvalidPayloadException;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Model\Timer\StartTimerPayload;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Repository\TimerRepository;
use App\Repository\UserRepository;
use App\Service\Synchronization\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;


#[AsTaggedItem(index: 'start.timer')]
readonly class Starter implements ProcessorInterface
{
    public function __construct(
        private TimerRepository $timerRepository,
        private UserRepository $userRepository,
        private ProjectRepository $projectRepository,
        private ClientRepository $clientRepository,
        private TaskRepository $taskRepository,
    ) {
    }

    public function sync(User $user, ActionPayloadInterface $payload): void
    {
        if (!$payload instanceof StartTimerPayload) {
            throw new \RuntimeException('Invalid payload');
        }

        $timer = Timer::fromUser($user, $payload->getId());

        $timer->setStartTime($payload->getStartTime());

        $client = null;
        if (($clientId = $payload->getClientId()) && !$client = $this->clientRepository->find($clientId)) {
            throw new InvalidPayloadException('Invalid client clientId');
        }
        $timer->setClient($client);


        $project = null;
        if (($projectId = $payload->getProjectId()) && !$project = $this->projectRepository->find($projectId)) {
            throw new InvalidPayloadException('Invalid project clientId');
        }
        $timer->setProject($project);


        $task = null;
        if (($taskId = $payload->getTaskId()) && !$task = $this->taskRepository->find($taskId)) {
            throw new InvalidPayloadException('Invalid task clientId');
        }
        $timer->setTask($task);

        $this->timerRepository->save($timer, true);

        $user->setTimer($timer);

        $this->userRepository->save($user, true);
    }
}
