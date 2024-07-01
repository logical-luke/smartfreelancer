<?php

declare(strict_types=1);

namespace App\Service\Synchronization\Timer;

use App\Entity\User;
use App\Exception\InvalidPayloadException;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Model\Timer\DeleteTimerPayload;
use App\Repository\TimerRepository;
use App\Repository\UserRepository;
use App\Service\Synchronization\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'delete.timer')]
readonly class Deleter implements ProcessorInterface
{
    public function __construct(
        private TimerRepository $timerRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function sync(User $user, ActionPayloadInterface $payload): void
    {
        if (!$payload instanceof DeleteTimerPayload) {
            throw new \RuntimeException('Invalid payload');
        }

        if (!$timer = $this->timerRepository->find($payload->getTimerId())) {
            throw new InvalidPayloadException('Invalid timer id');
        }

        if (!$timerOwner = $timer->getOwner()) {
            throw new InvalidPayloadException('Invalid timer owner');
        }

        if ($this->userRepository->find($payload->getUserId())?->getId()?->toRfc4122() !== $timerOwner->getId()?->toRfc4122()) {
            throw new InvalidPayloadException('Invalid timer owner');
        }

        $this->timerRepository->remove($timer, true);
    }
}
