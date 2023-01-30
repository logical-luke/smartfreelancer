<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Project;
use App\Model\Project\CreateProjectPayload;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;

class ProjectCreator
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
        private readonly UserRepository $userRepository,
        private readonly ClientRepository $clientRepository,
    ) {
    }

    public function __invoke(CreateProjectPayload $payload): Project
    {
        if (!$user = $this->userRepository->find($payload->getOwnerId())) {
            throw new \RuntimeException('User not found');
        }

        $project = Project::fromUser($user);

        $project->setName($payload->getName());
        $project->setDescription($payload->getDescription());

        if ($payload->getClientId() && $client = $this->clientRepository->find($payload->getClientId())) {
            $project->setClient($client);
        }

        $this->projectRepository->save($project, true);

        return $project;
    }
}
