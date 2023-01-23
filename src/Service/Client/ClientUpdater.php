<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Entity\Client;
use App\Model\UpdateClientPayload;
use App\Repository\ClientRepository;

class ClientUpdater
{
    public function __construct(
        private readonly ClientRepository $clientRepository,
    ) {
    }

    public function __invoke(UpdateClientPayload $payload): Client
    {
        if (!$client = $this->clientRepository->find($payload->getId())) {
            throw new \RuntimeException('Client not found');
        }

        // todo Add check if user is eligible to update client

        if ($name = $payload->getName()) {
            $client->setName($name);
        }

        if ($description = $payload->getDescription()) {
            $client->setDescription($description);
        }

        $this->clientRepository->flush();

        return $client;
    }
}
