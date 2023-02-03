<?php

declare(strict_types=1);

namespace App\Model\Time;

class TimeDTO
{
    protected function __construct(
        public int $time,
    ) {
    }

    public static function fromDateTime(\DateTimeInterface $dateTime): TimeDTO
    {
        return new self($dateTime->getTimestamp());
    }

    public static function fromTimestamp(int $timestamp): TimeDTO
    {
        return new self($timestamp);
    }

    public function getTime(): int
    {
        return $this->time;
    }
}
