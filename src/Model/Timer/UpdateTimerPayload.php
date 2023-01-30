<?php

declare(strict_types=1);

namespace App\Model\Timer;

use Symfony\Component\Uid\Uuid;

class UpdateTimerPayload
{
    protected function __construct(
        private readonly string $id,
        private readonly ?int $startTime,
        private readonly ?string $clientId,
        private readonly ?string $projectId,
        private readonly ?string $taskId,
    ) {
    }

    public static function from(array $payload): self
    {
        // todo Add validation here

        return new self(
            $payload['id'],
            $payload['startTime'] ?? null,
            $payload['clientId'] ?? null,
            $payload['projectId'] ?? null,
            $payload['taskId'] ?? null,
        );
    }

    public function getId(): Uuid
    {
        return Uuid::fromString($this->id);
    }

    public function getStartTime(): ?int
    {
        return $this->startTime;
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
