<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Timer;

class TimerDTO
{
    protected function __construct(
        public int $id,
        public ?int $projectId,
        public int $startTime,
        public ?int $clientId,
        public ?int $taskId,
    )
    {
    }

    public static function fromTimer(Timer $timer): self
    {
        return new self(
            $timer->getId(),
            $timer->getProject()?->getId(),
            $timer->getStartTime()->getTimestamp(),
            $timer->getClient()?->getId(),
            $timer->getTask()?->getId(),
        );
    }
}
