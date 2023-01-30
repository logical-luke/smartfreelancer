<?php

declare(strict_types=1);

namespace App\Model\Timer;

class UpdateTimerPayload
{
    protected function __construct(
        private readonly int $id,
        private readonly ?int $projectId,
        private readonly ?int $startTime,
        private readonly ?int $clientId,
        private readonly ?int $taskId,
    ) {
    }

    public static function from(array $payload): self
    {
        // todo Add validation here

        return new self(
            $payload['id'],
            $payload['projectId'] ?? null,
            $payload['startTime'] ?? null,
            $payload['clientId'] ?? null,
            $payload['taskId'] ?? null,
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProjectId(): ?int
    {
        return $this->projectId;
    }

    public function getStartTime(): ?int
    {
        return $this->startTime;
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
