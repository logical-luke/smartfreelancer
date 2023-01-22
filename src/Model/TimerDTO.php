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
        public ?string $subjectName,
    )
    {
    }

    public static function fromTimer(Timer $timer): self
    {
        $subjectName = ($project = $timer->getProject()) ? $project->getName() : null;

        return new self(
            $timer->getId(),
            $timer->getProject()?->getId(),
            $timer->getStartTime()->getTimestamp(),
            $subjectName,
        );
    }
}
