<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Client;

class ClientDTO
{
    protected function __construct(
        public readonly int $id,
        public readonly ?string $name,
        public readonly int $ownerId,
        public readonly ?string $description,
    )
    {
    }

    public static function fromClient(Client $client): self
    {
        return new self(
            $client->getId(),
            $client->getName(),
            $client->getOwner()->getId(),
            $client->getDescription(),
        );
    }
}
