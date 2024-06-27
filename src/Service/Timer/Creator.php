<?php

declare(strict_types=1);

namespace App\Service\Timer;

use App\Entity\Timer;
use App\Entity\User;
use App\Exception\InvalidPayloadException;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Model\Timer\CreateTimerPayload;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Repository\TimerRepository;
use App\Repository\UserRepository;
use App\Service\Synchronization\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'delete.timer')]
readonly class Creator implements ProcessorInterface
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
        if (!$payload instanceof CreateTimerPayload) {
            throw new \RuntimeException('Invalid payload');
        }

        $client = $payload->getClientId() ? $this->clientRepository->find($payload->getClientId()) : null;
        $project = $payload->getProjectId() ? $this->projectRepository->find($payload->getProjectId()) : null;
        $task = $payload->getTaskId() ? $this->taskRepository->find($payload->getTaskId()) : null;
        $timer = (Timer::fromUser($user, $payload->getTimerId()))
            ->setStartTime($payload->getStartTime())
            ->setClient($client)
            ->setProject($project)
            ->setTask($task);

        $this->timerRepository->save($timer, true);

        $user->setTimer($timer);

        $this->userRepository->save($user, true);
    }
}
