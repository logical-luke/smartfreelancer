<?php

declare(strict_types=1);

namespace App\Model\Timer;

use App\Exception\InvalidPayloadException;
use Symfony\Component\Uid\Uuid;

class StopTimerPayload
{
    protected function __construct(
        private readonly string $id,
        private readonly int $endTime,
        private readonly string $ownerId,
    ) {
    }

    public static function from(array $payload): self
    {
        if (!isset($payload['id'])) {
            throw new InvalidPayloadException('Missing timer id');
        }

        if (!isset($payload['endTime'])) {
            throw new InvalidPayloadException('Missing end time');
        }

        return new self(
            $payload['id'],
            $payload['endTime'],
            $payload['ownerId'],
        );
    }

    public function getId(): Uuid
    {
        return Uuid::fromString($this->id);
    }

    public function getEndTime(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable())->setTimestamp($this->endTime);
    }

    public function getOwnerId(): Uuid
    {
        return Uuid::fromString($this->ownerId);
    }
}
