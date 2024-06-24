<?php

declare(strict_types=1);

namespace App\Service\Timer;

use App\Entity\TimeEntry;
use App\Entity\User;
use App\Exception\InvalidPayloadException;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Model\TimeEntry\CreateTimeEntryPayload;
use App\Model\Timer\StopTimerPayload;
use App\Repository\TimerRepository;
use App\Repository\UserRepository;
use App\Service\Synchronization\ProcessorInterface;
use App\Service\TimeEntry\TimeEntryCreator;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[AsTaggedItem(index: 'stop.timer')]
class TimerStopper implements ProcessorInterface
{
    public function __construct(
        private TimerRepository $timerRepository,
        private TimeEntryCreator $timeEntryCreator,
        private UserRepository $userRepository,
    ) {
    }

    public function sync(User $user, ActionPayloadInterface $payload): void
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
    }
}
