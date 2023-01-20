<?php

declare(strict_types=1);

namespace App\Service\Timer;

use App\Exception\InvalidPayloadException;
use App\Model\StopTimerPayload;
use App\Repository\TimerRepository;

class TimerStopper
{
    public function __construct(private readonly TimerRepository $timerRepository)
    {
    }

    public function __invoke(StopTimerPayload $payload): void
    {
        if (!$timer = $this->timerRepository->find($payload->getTimerId())) {
            throw new InvalidPayloadException('Invalid timer id');
        }

        $this->timerRepository->remove($timer, true);
    }
}
