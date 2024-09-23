<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Project;
use App\Exception\ForbiddenActionException;
use App\Exception\InvalidPayloadException;
use App\Exception\ResourceNotFoundException;
use App\Model\Project\DeleteProjectPayload;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use App\Service\Project\Deleter as ProjectDeleter;
use App\Service\Timer\Updater as TimerUpdater;
use App\Service\Task\Deleter as TaskDeleter;
use App\Service\TimeEntry\Deleter as TimeEntryDeleter;

readonly class Deleter
{
    public function __construct(
        private UserRepository $userRepository,
        private ProjectRepository $projectRepository,
        private TimerUpdater $timerUpdater,
        private TaskDeleter $taskDeleter,
        private TimeEntryDeleter $timeEntryDeleter,
    ) {
    }

    public function __invoke(DeleteProjectPayload $payload): void
    {
        $project = $this->projectRepository->find($payload->getProjectId());
        if (!$project) {
            throw new ResourceNotFoundException('project', $payload->getProjectId());
        }

        $user = $this->userRepository->find($payload->getUserId());
        if (!$user) {
            throw new ResourceNotFoundException('user', $payload->getUserId());
        }

        if ($project->getOwner() !== $user) {
            throw new ForbiddenActionException('delete', 'project', $project->getId());
        }

        if ($timer = $project->getTimer()) {
            ($this->timerUpdater)($timer, null, $timer->getProject(), $timer->getTask(), $timer->getStartTime());
        }

        foreach ($project->getTasks() as $task) {
            ($this->taskDeleter)($task);
        }

        foreach ($project->getTimeEntries() as $timeEntry) {
            ($this->timeEntryDeleter)($timeEntry);
        }

        $this->projectRepository->remove($project, true);
    }
}
