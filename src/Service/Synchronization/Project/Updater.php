<?php

declare(strict_types=1);

namespace App\Service\Synchronization\Project;

use App\Entity\User;
use App\Model\Project\UpdateProjectPayload;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Service\Synchronization\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'update.project')]
readonly class Updater implements ProcessorInterface
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ClientRepository $clientRepository,
    ) {
    }

    public function sync(User $user, ActionPayloadInterface $payload): void
    {
        if (!$payload instanceof UpdateProjectPayload) {
            throw new \RuntimeException('Invalid payload');
        }

        if (!$project = $this->projectRepository->find($payload->getProjectId())) {
            throw new \RuntimeException('Project not found');
        }

        if (!$projectOwner = $project->getOwner()) {
            throw new \RuntimeException('Invalid project owner');
        }

        if ($user->getId()?->toRfc4122() !== $projectOwner->getId()?->toRfc4122()) {
            throw new \RuntimeException('Invalid project owner');
        }

        $client = $payload->getClientId() ? $client = $this->clientRepository->find($payload->getClientId()) : null;
        $project
            ->setName($payload->getName())
            ->setDescription($payload->getDescription())
            ->setClient($client);

        $this->projectRepository->save($project, true);
    }
}
