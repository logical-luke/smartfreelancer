<?php

declare(strict_types=1);

namespace App\Model;

class UpdateTimerPayload
{
    protected function __construct(
        private readonly int $id,
        private readonly ?int $projectId,
        private readonly ?int $startTime,
    ) {
    }

    public static function from(array $payload): self
    {
        // todo Add validation here

        return new self(
            $payload['id'],
            $payload['projectId'] ?? null,
            $payload['startTime'] ?? null,
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
}