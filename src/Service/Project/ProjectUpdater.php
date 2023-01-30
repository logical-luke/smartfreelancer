<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Project;
use App\Model\Project\UpdateProjectPayload;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;

class ProjectUpdater
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
        private readonly ClientRepository $clientRepository,
    ) {
    }

    public function __invoke(UpdateProjectPayload $payload): Project
    {
        if (!$project = $this->projectRepository->find($payload->getId())) {
            throw new \RuntimeException('Project not found');
        }


        // todo Add check if user is eligible to update project
        // if ($project->getOwner()->getId() !==) {
        //
        // }

        $project->setName($payload->getName());
        $project->setDescription($payload->getDescription());
        $project->setClient(null);
        if ($clientId = $payload->getClientId()) {
            $project->setClient($this->clientRepository->find($clientId));
        }

        $this->projectRepository->flush();

        return $project;
    }
}
