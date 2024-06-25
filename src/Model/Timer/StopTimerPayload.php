<?php

declare(strict_types=1);

namespace App\Model\Timer;

use App\Exception\InvalidPayloadException;
use App\Model\Synchronization\ActionPayloadInterface;
use Symfony\Component\Uid\Uuid;

readonly class StopTimerPayload implements ActionPayloadInterface
{
    protected function __construct(
        private string $timerId,
        private int $endTime,
        private string $userId,
    ) {
    }

    public static function from(Uuid $userId, array $payload): self
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
            $userId->toRfc4122()
        );
    }

    public function getTimerId(): Uuid
    {
        return Uuid::fromString($this->timerId);
    }

    public function getEndTime(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable())->setTimestamp($this->endTime);
    }

    public function getUserId(): Uuid
    {
        return Uuid::fromString($this->userId);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->timerId,
            'endTime' => $this->endTime,
            'userId' => $this->userId,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
