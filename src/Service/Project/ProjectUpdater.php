<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Project;
use App\Model\UpdateProjectPayload;
use App\Repository\ProjectRepository;

class ProjectUpdater
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
    ) {
    }

    public function __invoke(UpdateProjectPayload $payload): Project
    {
        if (!$project = $this->projectRepository->find($payload->getId())) {
            throw new \RuntimeException('Project not found');
        }

        // todo Add check if user is eligible to update project

        if ($name = $payload->getName()) {
            $project->setName($name);
        }

        if ($description = $payload->getDescription()) {
            $project->setDescription($description);
        }

        $this->projectRepository->flush();

        return $project;
    }
}
