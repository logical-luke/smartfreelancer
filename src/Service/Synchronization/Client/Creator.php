<?php

declare(strict_types=1);

namespace App\Service\Synchronization\Client;

use App\Entity\Client;
use App\Entity\User;
use App\Model\Client\CreateClientPayload;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Repository\ClientRepository;
use App\Service\Client\Creator as ClientCreator;
use App\Service\Synchronization\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'create.client')]
readonly class Creator implements ProcessorInterface
{
    public function __construct(
        private ClientCreator $creator,
    ) {
    }

    public function sync(User $user, ActionPayloadInterface $payload): void
    {
        if (!$payload instanceof CreateClientPayload) {
            throw new \RuntimeException('Invalid payload');
        }

        ($this->creator)(
            $user,
            $payload->getClientId(),
            $payload->getName(),
            $payload->getCreatedAt(),
            $payload->getEmail(),
            $payload->getEmail(),
            $payload->getAvatar(),
        );
    }
}
