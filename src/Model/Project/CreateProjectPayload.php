<?php

declare(strict_types=1);

namespace App\Model\Project;

use App\Exception\InvalidPayloadException;
use Symfony\Component\Uid\Uuid;

class CreateProjectPayload
{
    protected function __construct(
        private readonly string $ownerId,
        private readonly ?string $name,
        private readonly ?string $description,
        private readonly ?string $clientId,
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

    public function getOwnerId(): Uuid
    {
        return Uuid::fromString($this->ownerId);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getClientId(): ?Uuid
    {
        return $this->clientId ? Uuid::fromString($this->clientId) : null;
    }
}
