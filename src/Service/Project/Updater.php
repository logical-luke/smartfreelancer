<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Project;
use App\Exception\ForbiddenActionException;
use App\Exception\ResourceNotFoundException;
use App\Model\Project\UpdateProjectPayload;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;

readonly class Updater
{
    public function __construct(
        private UserRepository $userRepository,
        private ProjectRepository $projectRepository,
        private ClientRepository $clientRepository,
    ) {
    }

    public function __invoke(UpdateProjectPayload $payload): Project {
        if (!$user = $this->userRepository->find($payload->getUserId())) {
            throw new ResourceNotFoundException('user', $payload->getUserId());
        }

        if (!$project = $this->projectRepository->find($payload->getProjectId())) {
            throw new ResourceNotFoundException('project', $payload->getProjectId());
        }

        if (!$client = $this->clientRepository->find($payload->getClientId())) {
            throw new ResourceNotFoundException('client', $payload->getClientId());
        }

        if ($project->getOwner() !== $user) {
            throw new ForbiddenActionException('update', 'project', $project->getId());
        }

        $project
            ->setName($payload->getName())
            ->setClient($client)
            ->setDescription($payload->getDescription())
            ->setDueDate($payload->getDueDate())
            ->setAvatar($payload->getAvatar());

        $this->projectRepository->save($project, true);

        return $project;
    }
}
