<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Project;
use App\Model\DeleteProjectPayload;
use App\Repository\ProjectRepository;

class ProjectDeleter
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
    ) {
    }

    public function __invoke(DeleteProjectPayload $payload): void
    {
        if (!$project = $this->projectRepository->find($payload->getId())) {
            throw new \RuntimeException('Project not found');
        }

        // todo Add check if user is eligible to delete project

        $this->projectRepository->remove($project, true);
    }
}
