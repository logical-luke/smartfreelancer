<?php

declare(strict_types=1);

namespace App\Service\Timer;

use App\Entity\Timer;
use App\Exception\InvalidPayloadException;
use App\Model\CreateTimerPayload;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TimerCreator
{
    public function __construct(private readonly UserRepository $userRepository, private readonly ValidatorInterface $validator)
    {
    }

    public function __invoke(CreateTimerPayload $payload): Timer
    {
        $startTime = null;

        if (!$user = $this->userRepository->find($payload->getOwnerId())) {
            throw new InvalidPayloadException('Invalid owner id');
        }

        if ($startTimeTimestamp = $payload->getStartTimeTimestamp()) {
            if ($this->validator->validate($startTimeTimestamp) > 0) {
                throw new InvalidPayloadException('Invalid start time timestamp');
            }

            $startTime = (new DateTime())->setTimestamp($startTimeTimestamp);
        }

        return Timer::fromUser($user, $startTime);
    }
}
