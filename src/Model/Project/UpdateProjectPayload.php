<?php

declare(strict_types=1);

namespace App\Model\Project;

use App\Exception\InvalidPayloadException;
use App\Model\Synchronization\ActionPayloadInterface;
use Symfony\Component\Uid\Uuid;

readonly class UpdateProjectPayload implements ActionPayloadInterface
{
    protected function __construct(
        private string $projectId,
        private string $name,
        private ?string $description,
        private ?string $clientId,
        private string $ownerId,
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

        return new self(
            $payload['id'],
            $payload['name'] ?? null,
            $payload['description'] ?? null,
            $payload['clientId'] ?? null,
            $userId->toRfc4122(),
        );
    }

    public function getProjectId(): Uuid
    {
        return Uuid::fromString($this->projectId);
    }

    public function getName(): string
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

    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->projectId,
            'name' => $this->name,
            'description' => $this->description,
            'clientId' => $this->clientId,
            'userId' => $this->ownerId,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
