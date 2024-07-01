<?php

declare(strict_types=1);

namespace App\Service\Synchronization\Project;

use App\Entity\Project;
use App\Entity\User;
use App\Model\Project\CreateProjectPayload;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Service\Synchronization\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'create.project')]
readonly class Creator implements ProcessorInterface
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ClientRepository $clientRepository,
    ) {
    }

    public function sync(User $user, ActionPayloadInterface $payload): void
    {
        if (!$payload instanceof CreateProjectPayload) {
            throw new \RuntimeException('Invalid payload');
        }

        $client = $payload->getClientId() ? $client = $this->clientRepository->find($payload->getClientId()) : null;
        $project = (Project::fromUser($user, $payload->getProjectId(), $payload->getName()))
            ->setDescription($payload->getDescription())
            ->setClient($client);

        $this->projectRepository->save($project, true);
    }
}
