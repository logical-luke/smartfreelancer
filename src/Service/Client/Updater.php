<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Entity\Client;
use App\Exception\ForbiddenActionException;
use App\Exception\ResourceNotFoundException;
use App\Model\Client\UpdateClientPayload;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;

readonly class Updater
{
    public function __construct(
        private UserRepository $userRepository,
        private ClientRepository $clientRepository,
    ) {
    }

    public function __invoke(UpdateClientPayload $payload): Client {
        if (!$user = $this->userRepository->find($payload->getUserId())) {
            throw new ResourceNotFoundException('user', $payload->getUserId());
        }

        if (!$client = $this->clientRepository->find($payload->getClientId())) {
            throw new ResourceNotFoundException('client', $payload->getClientId());
        }

        if ($client->getOwner() !== $user) {
            throw new ForbiddenActionException('update', 'client', $client->getId());
        }

        $client
            ->setName($payload->getName())
            ->setEmail($payload->getEmail())
            ->setPhone($payload->getPhone())
            ->setAvatar($payload->getAvatarPath());

        $this->clientRepository->save($client, true);

        return $client;
    }
}
