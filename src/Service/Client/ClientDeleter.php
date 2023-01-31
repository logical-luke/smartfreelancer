<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Model\Client\DeleteClientPayload;
use App\Repository\ClientRepository;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: true)]
class ClientDeleter
{
    public function __construct(
        private readonly ClientRepository $clientRepository,
    ) {
    }

    public function __invoke(DeleteClientPayload $payload): void
    {
        if (!$client = $this->clientRepository->find($payload->getId())) {
            throw new \RuntimeException('Client not found');
        }

        // todo Add check if user is eligible to delete client

        if ($timer = $client->getTimer()) {
            $timer->setClient(null);
        }

        foreach ($client->getProjects() as $project) {
            $project->setClient(null);
        }

        foreach ($client->getTimeEntries() as $timeEntry) {
            $timeEntry->setClient(null);
        }

        $this->clientRepository->remove($client, true);
    }
}
