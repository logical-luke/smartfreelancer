<?php

declare(strict_types=1);

namespace App\Model\Timer;

use App\Exception\InvalidPayloadException;

class StopTimerPayload
{
    protected function __construct(
        private readonly int $timerId,
    )
    {
    }

    public static function from(array $array): self
    {
        if (!isset($array['timerId'])) {
            throw new InvalidPayloadException('Missing timer id');
        }

        return new self(
            $array['timerId'],
        );
    }

    public function getTimerId(): int
    {
        return $this->timerId;
    }
}
