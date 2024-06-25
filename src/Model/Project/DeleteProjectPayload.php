<?php

declare(strict_types=1);

namespace App\Model\Project;

use App\Exception\InvalidPayloadException;
use App\Model\Synchronization\ActionPayloadInterface;
use Symfony\Component\Uid\Uuid;

readonly class DeleteProjectPayload implements ActionPayloadInterface
{
    protected function __construct(
        private string $projectId,
        private string $ownerId,
    ) {
    }

    public static function from(
        Uuid $userId, array $payload
    ): self
    {
        if (!isset($payload['id'])) {
            throw new InvalidPayloadException('Missing project id');
        }

        return new self($payload['id'], $userId->toRfc4122());
    }

    public function getProjectId(): Uuid
    {
        return Uuid::fromString($this->projectId);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->projectId,
            'ownerId' => $this->ownerId,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
