<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Entity\Client;
use App\Model\Client\CreateClientPayload;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: true)]
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

        $client->setName($payload->getName());
        $client->setDescription($payload->getDescription());

        $this->clientRepository->save($client, true);

        return $client;
    }
}
