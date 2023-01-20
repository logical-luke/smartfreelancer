<?php

declare(strict_types=1);

namespace App\Model;

class CreateTimerPayload
{
    protected function __construct(
        private readonly int $ownerId,
        private readonly int $startTimeTimestamp,
        private readonly ?int $projectId,
    ) {
    }

    public static function from(array $payload): CreateTimerPayload
    {
        return new self(
            $payload['owner_id'],
            $payload['start_time_timestamp'],
            $payload['project_id'],
        );
    }

    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    public function getStartTimeTimestamp(): int
    {
        return $this->startTimeTimestamp;
    }

    public function getProjectId(): ?int
    {
        return $this->projectId;
    }
}
