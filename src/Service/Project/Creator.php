<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Project;
use App\Entity\User;
use App\Exception\ResourceNotFoundException;
use App\Model\Project\CreateProjectPayload;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Symfony\Component\Uid\Uuid;

readonly class Creator
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ClientRepository $clientRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(CreateProjectPayload $payload): Project {
        if (!$owner = $this->userRepository->find($payload->getOwnerId())) {
            throw new ResourceNotFoundException('user', $payload->getOwnerId());
        }

        if (!$client = $this->clientRepository->find($payload->getClientId())) {
            throw new ResourceNotFoundException('client', $payload->getClientId());
        }

        $project = (Project::from($owner, $payload->getName(), $client));

        $project->setDescription($payload->getDescription());

        $this->projectRepository->save($project, true);

        return $project;
    }
}
