<?php

declare(strict_types=1);

namespace App\Model\Timer;

use Symfony\Component\Uid\Uuid;

class CreateTimerPayload
{
    protected function __construct(
        private readonly string $ownerId,
        private readonly ?string $projectId,
        private readonly ?string $clientId,
        private readonly ?string $taskId,
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

    public function getOwnerId(): Uuid
    {
        return Uuid::fromString($this->ownerId);
    }

    public function getProjectId(): ?Uuid
    {
        return $this->projectId ? Uuid::fromString($this->projectId) : null;
    }

    public function getClientId(): ?Uuid
    {
        return $this->clientId ? Uuid::fromString($this->clientId) : null;
    }

    public function getTaskId(): ?Uuid
    {
        return $this->taskId ? Uuid::fromString($this->taskId) : null;
    }
}
