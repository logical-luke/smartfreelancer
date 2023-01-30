<?php

declare(strict_types=1);

namespace App\Model\Timer;

use App\Exception\InvalidPayloadException;
use Symfony\Component\Uid\Uuid;

class StopTimerPayload
{
    protected function __construct(
        private readonly string $timerId,
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

    public function getId(): Uuid
    {
        return Uuid::fromString($this->timerId);
    }
}
