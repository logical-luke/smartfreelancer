<?php

declare(strict_types=1);

namespace App\Service\TimeEntry;

use App\Entity\TimeEntry;
use App\Exception\InvalidPayloadException;
use App\Model\TimeEntry\CreateTimeEntryPayload;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Repository\TimeEntryRepository;
use App\Repository\TimerRepository;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: true)]
class TimeEntryCreator
{
    public function __construct(
        private TimeEntryRepository $timeEntryRepository,
        private readonly UserRepository $userRepository,
        private readonly ClientRepository $clientRepository,
        private readonly ProjectRepository $projectRepository,
        private readonly TaskRepository $taskRepository,
        private readonly TimerRepository $timerRepository,
    ) {
    }


    public function __invoke(CreateTimeEntryPayload $payload): TimeEntry
    {
        if ($payload->getTimerId()) {
            return $this->createTimeEntryFromTimer($payload);
        }

        return $this->createTimeEntryFromPayload($payload);
    }

    private function createTimeEntryFromPayload(CreateTimeEntryPayload $payload): ?TimeEntry
    {
        if (!$owner = $this->userRepository->find($payload->getOwnerId())) {
            throw new InvalidPayloadException('User not found');
        }

        $timeEntry = TimeEntry::fromUser($owner, $payload->getStartTime(), $payload->getEndTime());

        if ($clientId = $payload->getClientId()) {
            if (!$client = $this->clientRepository->find($clientId)) {
                throw new InvalidPayloadException('Client not found');
            }

            $timeEntry->setClient($client);
        }

        if ($projectId = $payload->getProjectId()) {
            if (!$project = $this->projectRepository->find($projectId)) {
                throw new InvalidPayloadException('Project not found');
            }

            $timeEntry->setProject($project);
        }

        if ($taskId = $payload->getTaskId()) {
            if (!$task = $this->taskRepository->find($taskId)) {
                throw new InvalidPayloadException('Task not found');
            }

            $timeEntry->setTask($task);
        }

        $this->timeEntryRepository->save($timeEntry, true);

        return $timeEntry;
    }

    private function createTimeEntryFromTimer(CreateTimeEntryPayload $payload): TimeEntry
    {
        if (!$timer = $this->timerRepository->find($payload->getTimerId())) {
            throw new InvalidPayloadException('Timer not found');
        }

        $timeEntry = TimeEntry::fromTimer($timer, $payload->getEndTime());

        if ($client = $timer->getClient()) {
            $timeEntry->setClient($client);
        }

        if ($project = $timer->getProject()) {
            $timeEntry->setProject($project);
        }

        if ($task = $timer->getTask()) {
            $timeEntry->setTask($task);
        }

        $this->timeEntryRepository->save($timeEntry, true);

        return $timeEntry;
    }
}
