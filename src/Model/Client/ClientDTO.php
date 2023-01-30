<?php

declare(strict_types=1);

namespace App\Model\Client;

use App\Entity\Client;

class ClientDTO
{
    protected function __construct(
        public readonly string $id,
        public readonly string $ownerId,
        public readonly ?string $name,
        public readonly ?string $description,
    )
    {
    }

    public static function fromClient(Client $client): self
    {
        return new self(
            $client->getId()?->toRfc4122(),
            $client->getOwner()?->getId()?->toRfc4122(),
            $client->getName(),
            $client->getDescription(),
        );
    }
}
