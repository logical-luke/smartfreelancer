<?php

declare(strict_types=1);

namespace App\Model\TimeEntry;

use App\Exception\InvalidPayloadException;
use Symfony\Component\Uid\Uuid;

class CreateTimeEntryPayload
{
    protected function __construct(
        private readonly ?string $ownerId,
        private readonly ?string $timerId,
        private readonly ?int $startTime,
        private readonly int $endTime,
        private readonly ?string $clientId,
        private readonly ?string $projectId,
        private readonly ?string $taskId,
    ) {
    }

    public static function from(array $payload): CreateTimeEntryPayload
    {
        if (!isset($payload['endTime'])) {
            throw new InvalidPayloadException('Missing end time');
        }

        if (!isset($payload['timerId']) && !isset($payload['ownerId'])) {
            throw new InvalidPayloadException('Missing timer id or owner id');
        }

        if (!isset($payload['timerId']) && !isset($payload['startTime'])) {
            throw new InvalidPayloadException('Missing timer id or start time');
        }

        return new self(
            $payload['ownerId'] ?? null,
            $payload['timerId'] ?? null,
            $payload['startTime'] ?? null,
            $payload['endTime'],
            $payload['clientId'] ?? null,
            $payload['projectId'] ?? null,
            $payload['taskId'] ?? null,
        );
    }

    public function getOwnerId(): ?string
    {
        return $this->ownerId;
    }

    public function getTimerId(): ?string
    {
        return $this->timerId;
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime ? (new \DateTimeImmutable())->setTimestamp($this->startTime) : null;
    }

    public function getEndTime(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable())->setTimestamp($this->endTime);
    }

    public function getClientId(): ?Uuid
    {
        return $this->clientId ? Uuid::fromString($this->clientId) : null;
    }

    public function getProjectId(): ?Uuid
    {
        return $this->projectId ? Uuid::fromString($this->projectId) : null;
    }

    public function getTaskId(): ?Uuid
    {
        return $this->taskId ? Uuid::fromString($this->taskId) : null;
    }
}
