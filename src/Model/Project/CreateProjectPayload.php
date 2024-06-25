<?php

declare(strict_types=1);

namespace App\Model\Project;

use App\Exception\InvalidPayloadException;
use Symfony\Component\Uid\Uuid;

readonly class CreateProjectPayload
{
    protected function __construct(
        private string $projectId,
        private string $ownerId,
        private ?string $name,
        private ?string $description,
        private ?string $clientId,
    ) {
    }

    public static function from(Uuid $userId, array $payload): self
    {
        if (!isset($payload['id'])) {
            throw new InvalidPayloadException('Missing project id');
        }

        if (!isset($payload['name'])) {
            throw new InvalidPayloadException('Missing name');
        }

        $payload = array_merge([
            'description' => null,
            'clientId' => null,
        ], $payload);

        return new self(
            $payload['id'],
            $userId->toRfc4122(),
            $payload['name'],
            $payload['description'],
            $payload['clientId']
        );
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

    public function getProjectId(): Uuid
    {
        return Uuid::fromString($this->projectId);
    }
}
