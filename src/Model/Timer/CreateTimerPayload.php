<?php

declare(strict_types=1);

namespace App\Model\Timer;

use App\Exception\InvalidPayloadException;
use App\Model\Synchronization\ActionPayloadInterface;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

readonly class CreateTimerPayload implements ActionPayloadInterface
{
    protected function __construct(
        private string $timerId,
        private string $userId,
        private int $startTime,
        private ?string $projectId,
        private ?string $clientId,
        private ?string $taskId,
    ) {
    }

    public static function from(Uuid $userId, array $payload): CreateTimerPayload
    {
        if (!isset($payload['id'])) {
            throw new InvalidPayloadException('Missing timer id');
        }

        if (!isset($payload['startTime'])) {
            throw new InvalidPayloadException('Missing start time');
        }

        $payload = array_merge([
            'projectId' => null,
            'clientId' => null,
            'taskId' => null,
        ], $payload);

        return new self(
            $payload['id'],
            $userId->toRfc4122(),
            $payload['startTime'],
            $payload['projectId'],
            $payload['clientId'],
            $payload['taskId'],
        );
    }

    public function getTimerId(): Uuid
    {
        return Uuid::fromString($this->timerId);
    }

    public function getUserId(): Uuid
    {
        return Uuid::fromString($this->userId);
    }

    public function getStartTime(): DateTimeImmutable
    {
        return (new DateTimeImmutable())->setTimestamp($this->startTime);
    }

    public function getProjectId(): ?Uuid
    {
        return $this->projectId ? Uuid::fromString($this->projectId) : null;
    }

    public function getClientId(): ?Uuid
    {
        return $this->clientId ? Uuid::fromString($this->clientId) : null;
    }

    public function getTaskId(): ?Uuid
    {
        return $this->taskId ? Uuid::fromString($this->taskId) : null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->timerId,
            'userId' => $this->userId,
            'startTime' => $this->startTime,
            'projectId' => $this->projectId,
            'clientId' => $this->clientId,
            'taskId' => $this->taskId,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
