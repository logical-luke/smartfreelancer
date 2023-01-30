<?php

declare(strict_types=1);

namespace App\Service\TimeEntry;

use App\Entity\TimeEntry;

class TimeEntryCreator
{
    public function __invoke(): TimeEntry
    {
        $timeEntry = new TimeEntry();
    }
}
