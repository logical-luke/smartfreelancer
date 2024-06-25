<?php

declare(strict_types=1);

namespace App\Model\Timer;

use App\Entity\Timer;

readonly class TimerDTO
{
    protected function __construct(
        public ?string $id,
        public int $startTime,
        public ?string $clientId,
        public ?string $projectId,
        public ?string $taskId,
    ) {
    }

    public static function fromTimer(Timer $timer): self
    {
        return new self(
            $timer->getId()?->toRfc4122(),
            $timer->getStartTime()?->getTimestamp(),
            $timer->getClient()?->getId()?->toRfc4122(),
            $timer->getProject()?->getId()?->toRfc4122(),
            $timer->getTask()?->getId()?->toRfc4122(),
        );
    }
}
