<?php

declare(strict_types=1);

namespace App\Service\Synchronization\Client;

use App\Entity\User;
use App\Model\Client\UpdateClientPayload;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Repository\ClientRepository;
use App\Service\Client\Updater as ClientUpdater;
use App\Service\Synchronization\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'update.client')]
readonly class Updater implements ProcessorInterface
{
    public function __construct(
        private ClientRepository $clientRepository,
        private ClientUpdater $clientUpdater,
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

        ($this->clientUpdater)($client, $payload->getName(), $payload->getEmail(), $payload->getPhone(), $payload->getAvatar());
    }
}
