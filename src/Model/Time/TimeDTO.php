<?php

declare(strict_types=1);

namespace App\Model\Time;

use DateTimeInterface;

readonly class TimeDTO
{
    protected function __construct(
        public int $time,
    ) {
    }

    public static function fromDateTime(DateTimeInterface $dateTime): TimeDTO
    {
        return new self($dateTime->getTimestamp());
    }

    public function getTime(): int
    {
        return $this->time;
    }
}
