<?php

declare(strict_types=1);

namespace App\Service\Synchronization;

use App\Service\Timer\TimerCreator;
use App\Service\Timer\TimerStopper;

readonly class SynchronizationQueueServiceFactory
{
    public function __construct(
        private TimerCreator $timerCreator,
        private TimerStopper $timerStopper,
    )
    {
    }

    public function __invoke(string $classname): object
    {
        return match($classname) {
            TimerCreator::class => $this->timerCreator,
            TimerStopper::class => $this->timerStopper,
            default => throw new \InvalidArgumentException(sprintf('Unknown synchronization service: %s', $classname)),
        };
    }
}
