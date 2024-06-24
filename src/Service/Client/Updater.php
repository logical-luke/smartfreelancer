<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Entity\Client;
use App\Entity\User;
use App\Model\Client\UpdateClientPayload;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Repository\ClientRepository;
use App\Service\Synchronization\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[AsTaggedItem(index: 'update.client')]
readonly class Updater implements ProcessorInterface
{
    public function __construct(
        private readonly ClientRepository $clientRepository,
    ) {
    }

    public function sync(User $user, ActionPayloadInterface $payload): void
    {
        if (!$payload instanceof UpdateClientPayload) {
            throw new \RuntimeException('Invalid payload');
        }

        if (!$client = $this->clientRepository->find($payload->getClientId())) {
            throw new \RuntimeException('Client not found');
        }

        $client
            ->setName($payload->getName())
            ->setEmail($payload->getEmail())
            ->setPhone($payload->getPhone())
            ->setAvatar($payload->getAvatar());

        $this->clientRepository->save($client, true);
    }
}
