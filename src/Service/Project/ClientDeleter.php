<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Entity\Client;
use App\Model\DeleteClientPayload;
use App\Repository\ClientRepository;

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

        $this->clientRepository->remove($client, true);
    }
}
