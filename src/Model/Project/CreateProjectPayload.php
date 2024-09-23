<?php

declare(strict_types=1);

namespace App\Model\Project;

use App\Entity\User;
use App\Exception\MissingPayloadFieldException;
use Symfony\Component\Uid\Uuid;

readonly class CreateProjectPayload
{
    protected function __construct(
        private Uuid $ownerId,
        private Uuid $clientId,
        private string $name,
        private ?string $description,
        private ?string $dueDate
    ) {
    }

    public static function from(User $owner, array $payload): self
    {
        if (!isset($payload['name'])) {
            throw new MissingPayloadFieldException('name');
        }

        if (!isset($payload['clientId'])) {
            throw new MissingPayloadFieldException('clientId');
        }

        return new self(
            $owner->getId(),
            Uuid::fromString($payload['clientId']),
            $payload['name'],
            !isset($payload['description']) || $payload['description'] === '' ? null : $payload['description'],
            $payload['dueDate'] ?? null
        );
    }

    public function getOwnerId(): Uuid
    {
        return $this->ownerId;
    }

    public function getClientId(): Uuid
    {
        return $this->clientId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDueDate(): ?string
    {
        return $this->dueDate;
    }
}
