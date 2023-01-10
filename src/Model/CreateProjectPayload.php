<?php

declare(strict_types=1);

namespace App\Model;

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
        // todo Implement validation of params

        $payload = array_merge([
            'name' => null,
            'description' => null,
            'ownerId' => null,
        ], $payload);

        return new self($payload['ownerId'], $payload['name'], $payload['description']);
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
