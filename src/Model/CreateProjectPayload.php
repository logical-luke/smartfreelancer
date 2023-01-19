<?php

declare(strict_types=1);

namespace App\Model;

use App\Exception\InvalidPayloadException;

class CreateProjectPayload
{
    protected function __construct(
        private readonly int $ownerId,
        private readonly ?string $name,
        private readonly ?string $description
    ) {
    }

    public static function from(array $payload): self
    {
        if (!isset($payload['owner_id'])) {
            throw new InvalidPayloadException('Missing owner_id');
        }

        if (!isset($payload['name'])) {
            throw new InvalidPayloadException('Missing name');
        }

        $payload = array_merge([
            'name' => null,
            'description' => null,
            'owner_id' => null,
        ], $payload);

        return new self($payload['owner_id'], $payload['name'], $payload['description']);
    }

    public function getOwnerId(): int
    {
        return $this->ownerId;
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
