<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Entity\Client;
use App\Model\Client\CreateClientPayload;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use App\Service\Synchronization\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'create.client')]
readonly class Creator implements ProcessorInterface
{
    public function __construct(
        private ClientRepository $clientRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function sync(ActionPayloadInterface $payload): void
    {
        if (!$payload instanceof CreateClientPayload) {
            throw new \RuntimeException('Invalid payload');
        }

        if (!$user = $this->userRepository->find($payload->getUserId())) {
            throw new \RuntimeException('User not found');
        }

        $client = (Client::fromUser($user))
            ->setName($payload->getName())
            ->setEmail($payload->getEmail())
            ->setPhone($payload->getPhone())
            ->setAvatar($payload->getAvatar());

        $this->clientRepository->save($client, true);
    }
}
