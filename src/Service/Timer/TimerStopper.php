<?php

declare(strict_types=1);

namespace App\Service\Timer;

use App\Entity\TimeEntry;
use App\Exception\InvalidPayloadException;
use App\Model\TimeEntry\CreateTimeEntryPayload;
use App\Model\Timer\StopTimerPayload;
use App\Repository\TimerRepository;
use App\Repository\UserRepository;
use App\Service\TimeEntry\TimeEntryCreator;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: true)]
class TimerStopper
{
    public function __construct(
        private readonly TimerRepository $timerRepository,
        private readonly TimeEntryCreator $timeEntryCreator,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(StopTimerPayload $payload): TimeEntry
    {
        if (!$timer = $this->timerRepository->find($payload->getId())) {
            throw new InvalidPayloadException('Invalid timer id');
        }

        if (!$timerOwner = $timer->getOwner()) {
            throw new InvalidPayloadException('Invalid timer owner');
        }

        $timeEntry = ($this->timeEntryCreator)(CreateTimeEntryPayload::from([
            'timerId' => $timer->getId()?->toRfc4122(),
            'endTime' => $payload->getEndTime()->getTimestamp(),
        ]));

        $timerOwner->setTimer(null);

        $this->userRepository->save($timerOwner, true);

        $this->timerRepository->remove($timer, true);

        return $timeEntry;
    }
}
