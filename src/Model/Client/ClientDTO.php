<?php

declare(strict_types=1);

namespace App\Model\Client;

use App\Entity\Client;

readonly class ClientDTO
{
    protected function __construct(
        public string  $id,
        public string  $ownerId,
        public ?string $name,
        public ?string $avatar,
        public ?string $email,
    ) {
    }

    public static function fromClient(Client $client): self
    {
        return new self(
            $client->getId()?->toRfc4122(),
            $client->getOwner()?->getId()?->toRfc4122(),
            $client->getName(),
            $client->getAvatar(),
            $client->getEmail(),
        );
    }
}
