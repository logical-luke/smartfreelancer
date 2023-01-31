<?php

declare(strict_types=1);

namespace App\Service\TimeEntry;

use App\Entity\TimeEntry;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: true)]
class TimeEntryCreator
{
    public function __invoke(): TimeEntry
    {
        $timeEntry = new TimeEntry();
    }
}
