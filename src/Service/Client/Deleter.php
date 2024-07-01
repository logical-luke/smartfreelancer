<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Service\Project\Deleter as ProjectDeleter;
use App\Service\Timer\Updater as TimerUpdater;
use App\Service\Task\Deleter as TaskDeleter;
use App\Service\TimeEntry\Deleter as TimeEntryDeleter;

readonly class Deleter
{
    public function __construct(
        private ClientRepository $clientRepository,
        private ProjectDeleter $projectDeleter,
        private TimerUpdater $timerUpdater,
        private TaskDeleter $taskDeleter,
        private TimeEntryDeleter $timeEntryDeleter,
    ) {
    }

    public function __invoke(Client $client): void
    {
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
