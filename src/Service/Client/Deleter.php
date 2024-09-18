<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Entity\Client;
use App\Exception\ForbiddenActionException;
use App\Exception\InvalidPayloadException;
use App\Exception\ResourceNotFoundException;
use App\Model\Client\DeleteClientPayload;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use App\Service\Project\Deleter as ProjectDeleter;
use App\Service\Timer\Updater as TimerUpdater;
use App\Service\Task\Deleter as TaskDeleter;
use App\Service\TimeEntry\Deleter as TimeEntryDeleter;

readonly class Deleter
{
    public function __construct(
        private UserRepository $userRepository,
        private ClientRepository $clientRepository,
        private ProjectDeleter $projectDeleter,
        private TimerUpdater $timerUpdater,
        private TaskDeleter $taskDeleter,
        private TimeEntryDeleter $timeEntryDeleter,
    ) {
    }

    public function __invoke(DeleteClientPayload $payload): void
    {
        $client = $this->clientRepository->find($payload->getClientId());
        if (!$client) {
            throw new ResourceNotFoundException('client', $payload->getClientId());
        }

        $user = $this->userRepository->find($payload->getUserId());
        if (!$user) {
            throw new ResourceNotFoundException('user', $payload->getUserId());
        }

        if ($client->getOwner() !== $user) {
            throw new ForbiddenActionException('delete', 'client', $client->getId());
        }

        if ($timer = $client->getTimer()) {
            ($this->timerUpdater)($timer, null, $timer->getProject(), $timer->getTask(), $timer->getStartTime());
        }

        foreach ($client->getProjects() as $project) {
            ($this->projectDeleter)($project);
        }

        foreach ($client->getTasks() as $task) {
            ($this->taskDeleter)($task);
        }

        foreach ($client->getTimeEntries() as $timeEntry) {
            ($this->timeEntryDeleter)($timeEntry);
        }

        $this->clientRepository->remove($client, true);
    }
}
