<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Entity\Client;
use App\Entity\User;
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
    ) {
    }

    public function sync(User $user, ActionPayloadInterface $payload): void
    {
        if (!$payload instanceof CreateClientPayload) {
            throw new \RuntimeException('Invalid payload');
        }

        $client = (Client::from($user, $payload->getClientId(), $payload->getName(), $payload->getCreatedAt()))
            ->setEmail($payload->getEmail())
            ->setPhone($payload->getPhone())
            ->setAvatar($payload->getAvatar());


        $this->clientRepository->save($client, true);
    }
}
