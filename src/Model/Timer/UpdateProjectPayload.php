<?php

declare(strict_types=1);

namespace App\Model\Timer;

class UpdateProjectPayload
{
    protected function __construct(
        private readonly int $id,
        private readonly ?string $name,
        private readonly ?string $description,
        private readonly ?int $clientId,
    ) {
    }

    public static function from(array $payload): self
    {
        // todo Add validation here

        return new self(
            $payload['id'],
            $payload['name'] ?? null,
            $payload['description'] ?? null,
            $payload['clientId'] ?? null,
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }
}
