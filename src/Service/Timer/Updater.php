<?php

declare(strict_types=1);

namespace App\Service\Timer;

use App\Entity\Timer;
use App\Entity\User;
use App\Exception\InvalidPayloadException;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Model\Timer\UpdateTimerPayload;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Repository\TimerRepository;
use App\Repository\UserRepository;
use App\Service\Synchronization\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[AsTaggedItem(index: 'update.timer')]
readonly class Updater implements ProcessorInterface
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
        if (!$payload instanceof UpdateTimerPayload) {
            throw new \RuntimeException('Invalid payload');
        }

        if (!$timer = $this->timerRepository->find($payload->getTimerId())) {
            throw new \RuntimeException('Timer not found');
        }

        if (!$timerOwner = $timer->getOwner()) {
            throw new InvalidPayloadException('Invalid timer owner');
        }

        if ($this->userRepository->find($payload->getUserId())?->getId()?->toRfc4122() !== $timerOwner->getId()?->toRfc4122()) {
            throw new InvalidPayloadException('Invalid timer owner');
        }

        $client = $payload->getClientId() ? $this->clientRepository->find($payload->getClientId()) : null;
        $project = $payload->getProjectId() ? $this->projectRepository->find($payload->getProjectId()) : null;
        $task = $payload->getTaskId() ? $this->taskRepository->find($payload->getTaskId()) : null;
        $timer
            ->setStartTime($payload->getStartTime())
            ->setClient($client)
            ->setProject($project)
            ->setTask($task);

        $this->timerRepository->save($timer, true);
    }
}
