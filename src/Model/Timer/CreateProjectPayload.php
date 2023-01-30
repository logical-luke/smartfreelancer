<?php

declare(strict_types=1);

namespace App\Model\Timer;

use App\Exception\InvalidPayloadException;

class CreateProjectPayload
{
    protected function __construct(
        private readonly int $ownerId,
        private readonly ?string $name,
        private readonly ?string $description,
        private readonly ?int $clientId,
    ) {
    }

    public static function from(array $payload): self
    {
        if (!isset($payload['ownerId'])) {
            throw new InvalidPayloadException('Missing owner id');
        }

        if (!isset($payload['name'])) {
            throw new InvalidPayloadException('Missing name');
        }

        $payload = array_merge([
            'name' => null,
            'description' => null,
            'ownerId' => null,
            'clientId' => null,
        ], $payload);

        return new self($payload['ownerId'], $payload['name'], $payload['description'], $payload['clientId']);
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

    public function getClientId()
    {
        return $this->clientId;
    }
}
