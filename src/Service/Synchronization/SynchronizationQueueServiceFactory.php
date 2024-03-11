<?php

declare(strict_types=1);

namespace App\Service\Synchronization;

use App\Service\TimeEntry\TimeEntryCreator;
use App\Service\Timer\TimerCreator;
use App\Service\Timer\TimerStopper;
use App\Service\Timer\TimerUpdater;

readonly class SynchronizationQueueServiceFactory
{
    public function __construct(
        private TimerCreator $timerCreator,
        private TimerStopper $timerStopper,
        private TimerUpdater $timerUpdater,
        private TimeEntryCreator $timeEntryCreator,
    )
    {
    }

    public function __invoke(string $classname): object
    {
        return match($classname) {
            TimerCreator::class => $this->timerCreator,
            TimerStopper::class => $this->timerStopper,
            TimerUpdater::class => $this->timerUpdater,
            TimeEntryCreator::class => $this->timeEntryCreator,
            default => throw new \InvalidArgumentException(sprintf('Unknown synchronization service: %s', $classname)),
        };
    }
}
