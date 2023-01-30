<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Project;
use App\Model\DeleteProjectPayload;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;

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

        $this->projectRepository->remove($project, true);
    }
}
