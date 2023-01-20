<?php

declare(strict_types=1);

namespace App\Model;

class CreateTimerPayload
{
    protected function __construct(
        private readonly int $ownerId,
        private readonly ?int $projectId,
    ) {
    }

    public static function from(array $payload): CreateTimerPayload
    {
        return new self(
            $payload['ownerId'],
            $payload['projectId'] ?? null,
        );
    }

    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    public function getProjectId(): ?int
    {
        return $this->projectId;
    }
}
