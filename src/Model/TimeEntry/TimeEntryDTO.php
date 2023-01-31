<?php

declare(strict_types=1);

namespace App\Model\TimeEntry;

use App\Entity\TimeEntry;

class TimeEntryDTO
{
    protected function __construct(
        public string $id,
        public string $ownerId,
        public int $startTime,
        public int $endTime,
        public ?string $clientId,
        public ?string $projectId,
        public ?string $taskId,
    ){
    }

    public static function fromTimeEntry(TimeEntry $timeEntry): self
    {
        return new self(
            $timeEntry->getId()?->toRfc4122(),
            $timeEntry->getOwner()?->getId()?->toRfc4122(),
            $timeEntry->getStartTime()?->getTimestamp(),
            $timeEntry->getEndTime()?->getTimestamp(),
            $timeEntry->getClient()?->getId()?->toRfc4122(),
            $timeEntry->getProject()?->getId()?->toRfc4122(),
            $timeEntry->getTask()?->getId()?->toRfc4122(),
        );
    }
}
