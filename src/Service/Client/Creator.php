<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Entity\Client;
use App\Entity\User;
use App\Exception\ResourceNotFoundException;
use App\Model\Client\CreateClientPayload;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Symfony\Component\Uid\Uuid;

readonly class Creator
{
    public function __construct(
        private ClientRepository $clientRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(CreateClientPayload $payload): Client {
        if (!$owner = $this->userRepository->find($payload->getOwnerId())) {
            throw new ResourceNotFoundException('user', $payload->getOwnerId());
        }

        $client = (Client::from($owner, $payload->getName()));

        $client
            ->setEmail($payload->getEmail())
            ->setPhone($payload->getPhone())
            ->setAvatar($payload->getAvatarPath());

        $this->clientRepository->save($client, true);

        return $client;
    }
}
