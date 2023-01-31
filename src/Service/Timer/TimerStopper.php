<?php

declare(strict_types=1);

namespace App\Service\Timer;

use App\Exception\InvalidPayloadException;
use App\Model\TimeEntry\CreateTimeEntryPayload;
use App\Model\Timer\StopTimerPayload;
use App\Repository\TimerRepository;
use App\Service\TimeEntry\TimeEntryCreator;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: true)]
class TimerStopper
{
    public function __construct(
        private readonly TimerRepository $timerRepository,
        private readonly TimeEntryCreator $timeEntryCreator
    ) {
    }

    public function __invoke(StopTimerPayload $payload): void
    {
        if (!$timer = $this->timerRepository->find($payload->getId())) {
            throw new InvalidPayloadException('Invalid timer id');
        }

        ($this->timeEntryCreator)(CreateTimeEntryPayload::from([
            'timerId' => $timer->getId()?->toRfc4122(),
            'endTime' => $payload->getEndTime()->getTimestamp(),
        ]));

        $this->timerRepository->remove($timer, true);
    }
}
