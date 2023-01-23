<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Entity\Client;
use App\Model\CreateClientPayload;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;

class ClientCreator
{
    public function __construct(
        private readonly ClientRepository $clientRepository,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(CreateClientPayload $payload): Client
    {
        if (!$user = $this->userRepository->find($payload->getOwnerId())) {
            throw new \RuntimeException('User not found');
        }

        $client = Client::fromUser($user);

        if ($payload->getName()) {
            $client->setName($payload->getName());
        }

        $this->clientRepository->save($client, true);

        return $client;
    }
}