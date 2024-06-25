<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Entity\User;
use App\Model\Client\DeleteClientPayload;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Repository\ClientRepository;
use App\Service\Synchronization\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'delete.client')]
readonly class Deleter implements ProcessorInterface
{
    public function __construct(
        private ClientRepository $clientRepository,
    ) {
    }
    public function sync(User $user, ActionPayloadInterface $payload): void
    {
        if (!$payload instanceof DeleteClientPayload) {
            throw new \RuntimeException('Invalid payload');
        }

        if (!$client = $this->clientRepository->find($payload->getClientId())) {
            throw new \RuntimeException('Client not found');
        }

        if ($timer = $client->getTimer()) {
            $timer->setClient(null);
        }

        foreach ($client->getProjects() as $project) {
            $project->setClient(null);
        }

        foreach ($client->getTasks() as $task) {
            $task->setClient(null);
        }

        foreach ($client->getTimeEntries() as $timeEntry) {
            $timeEntry->setClient(null);
        }

        $this->clientRepository->remove($client, true);
    }
}
