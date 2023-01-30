<?php

declare(strict_types=1);

namespace App\Model\Client;

use Symfony\Component\Uid\Uuid;

class UpdateClientPayload
{
    protected function __construct(
        private readonly string $id,
        private readonly ?string $name,
        private readonly ?string $description,
    ) {
    }

    public static function from(array $payload): self
    {
        // todo Add validation here

        return new self(
            $payload['id'],
            $payload['name'] ?? null,
            $payload['description'] ?? null,
        );
    }

    public function getId(): Uuid
    {
        return Uuid::fromString($this->id);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
