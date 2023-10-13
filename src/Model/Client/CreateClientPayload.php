<?php

declare(strict_types=1);

namespace App\Model\Client;

use App\Exception\InvalidPayloadException;
use Symfony\Component\Uid\Uuid;

readonly class CreateClientPayload
{
    protected function __construct(
        private string  $ownerId,
        private ?string $name,
        private ?string $description,
        private ?string $email,
        private ?string $industry,
    )
    {
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getIndustry(): ?string
    {
        return $this->industry;
    }

    public
    static function from(array $payload): self
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
        ], $payload);

        return new self($payload['ownerId'], $payload['name'], $payload['description'], $payload['email'], $payload['industry']);
    }

    public
    function getOwnerId(): Uuid
    {
        return Uuid::fromString($this->ownerId);
    }

    public
    function getName(): ?string
    {
        return $this->name;
    }

    public
    function getDescription(): ?string
    {
        return $this->description;
    }
}
