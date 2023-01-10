<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Project;
use App\Model\CreateProjectPayload;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;

class ProjectCreator
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(CreateProjectPayload $payload): Project
    {
        if (!$user = $this->userRepository->find($payload->getOwnerId())) {
            throw new \RuntimeException('User not found');
        }

        $project = Project::fromUser($user);

        if ($payload->getName()) {
            $project->setName($payload->getName());
        }

        $this->projectRepository->save($project, true);

        return $project;
    }
}
