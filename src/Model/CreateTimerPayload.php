<?php

declare(strict_types=1);

namespace App\Model;

class CreateTimerPayload
{
    protected function __construct(
        private readonly int $ownerId,
        private readonly ?int $projectId,
        private readonly ?int $clientId,
        private readonly ?int $taskId,
    ) {
    }

    public static function from(array $payload): CreateTimerPayload
    {
        return new self(
            $payload['ownerId'],
            $payload['projectId'] ?? null,
            $payload['clientId'] ?? null,
            $payload['taskId'] ?? null,
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

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function getTaskId(): ?int
    {
        return $this->taskId;
    }
}
