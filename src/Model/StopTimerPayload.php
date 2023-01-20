<?php

declare(strict_types=1);

namespace App\Model;

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
        if (!isset($array['timer_id'])) {
            throw new InvalidPayloadException('Missing timer id');
        }

        return new self(
            $array['timer_id'],
        );
    }

    public function getTimerId(): int
    {
        return $this->timerId;
    }
}
