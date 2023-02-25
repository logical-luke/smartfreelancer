<?php

declare(strict_types=1);

namespace App\Model\Timer;

use Symfony\Component\Uid\Uuid;

readonly class CreateTimerPayload
{
    protected function __construct(
        private ?string $id,
        private string $ownerId,
        private int $startTime,
        private ?string $projectId,
        private ?string $clientId,
        private ?string $taskId,
    ) {
    }

    public static function from(array $payload): CreateTimerPayload
    {
        return new self(
            $payload['id'] ?? null,
            $payload['ownerId'],
            $payload['startTime'],
            $payload['projectId'] ?? null,
            $payload['clientId'] ?? null,
            $payload['taskId'] ?? null,
        );
    }

    public function getId(): ?Uuid
    {
        return $this->id ? Uuid::fromString($this->id) : null;
    }

    public function getOwnerId(): Uuid
    {
        return Uuid::fromString($this->ownerId);
    }

    public function getStartTime(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable())->setTimestamp($this->startTime);
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
