<?php

declare(strict_types=1);

namespace App\Model\Task;

use App\Exception\InvalidPayloadException;
use App\Model\Synchronization\ActionPayloadInterface;
use Symfony\Component\Uid\Uuid;

readonly class DeleteTaskPayload implements ActionPayloadInterface
{
    protected function __construct(
        private string $taskId,
        private string $ownerId,
    ) {
    }

    public static function from(Uuid $userId, array $payload): self
    {
        if (!isset($payload['id'])) {
            throw new InvalidPayloadException('Missing task id');
        }

        return new self(
            $payload['id'],
            $userId->toRfc4122(),
        );
    }

    public function getTaskId(): Uuid
    {
        return Uuid::fromString($this->taskId);
    }

    public function getOwnerId(): Uuid
    {
        return Uuid::fromString($this->ownerId);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->taskId,
            'ownerId' => $this->ownerId,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
