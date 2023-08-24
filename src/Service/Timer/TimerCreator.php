<?php

declare(strict_types=1);

namespace App\Service\Timer;

use App\Entity\Timer;
use App\Exception\InvalidPayloadException;
use App\Model\Timer\CreateTimerPayload;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Repository\TimerRepository;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: true)]
class TimerCreator
{
    public function __construct(
        private readonly TimerRepository $timerRepository,
        private readonly UserRepository $userRepository,
        private readonly ProjectRepository $projectRepository,
        private readonly ClientRepository $clientRepository,
        private readonly TaskRepository $taskRepository,
    ) {
    }

    public function __invoke(CreateTimerPayload $payload): Timer
    {
        if (!$user = $this->userRepository->find($payload->getOwnerId())) {
            throw new InvalidPayloadException('Invalid owner id');
        }

        $timer = Timer::fromUser($user, $payload->getId());
        $timer->setStartTime($payload->getStartTime());

        if ($projectId = $payload->getProjectId()) {
            if (!$project = $this->projectRepository->find($projectId)) {
                throw new InvalidPayloadException('Invalid project id');
            }
            $timer->setProject($project);
        }

        if ($clientId = $payload->getClientId()) {
            if (!$client = $this->clientRepository->find($clientId)) {
                throw new InvalidPayloadException('Invalid client id');
            }
            $timer->setClient($client);
        }

        if ($taskId = $payload->getTaskId()) {
            if (!$task = $this->taskRepository->find($taskId)) {
                throw new InvalidPayloadException('Invalid task id');
            }
            $timer->setTask($task);
        }

        $this->timerRepository->save($timer, true);

        $user->setTimer($timer);

        $this->userRepository->save($user, true);

        return $timer;
    }
}
