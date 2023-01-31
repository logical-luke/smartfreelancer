<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Model\Project\DeleteProjectPayload;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: true)]
class ProjectDeleter
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
        private readonly TaskRepository $taskRepository,
    ) {
    }

    public function __invoke(DeleteProjectPayload $payload): void
    {
        if (!$project = $this->projectRepository->find($payload->getId())) {
            throw new \RuntimeException('Project not found');
        }

        foreach ($project->getTasks() as $task) {
            // todo Make it async
            $this->taskRepository->remove($task);
        }

        if ($timer = $project->getTimer()) {
            $timer->setProject(null);
        }

        foreach ($project->getTimeEntries() as $timeEntry) {
            $timeEntry->setProject(null);
        }

        $this->projectRepository->remove($project, true);
    }
}
