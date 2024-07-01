<?php

declare(strict_types=1);

namespace App\Service\Synchronization\Client;

use App\Entity\User;
use App\Model\Client\DeleteClientPayload;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Repository\ClientRepository;
use App\Service\Client\Deleter as ClientDeleter;
use App\Service\Synchronization\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'delete.client')]
readonly class Deleter implements ProcessorInterface
{
    public function __construct(
        private ClientRepository $clientRepository,
        private ClientDeleter $clientDeleter,
    ) {
    }
    public function sync(User $user, ActionPayloadInterface $payload): void
    {
        if (!$payload instanceof DeleteClientPayload) {
            throw new \RuntimeException('Invalid payload');
        }

        if (!$client = $this->clientRepository->find($payload->getClientId())) {
            throw new \RuntimeException('Client not found');
        }

        ($this->clientDeleter)($client);
    }
}
