<?php

declare(strict_types=1);

namespace App\Model\Synchronization;

use Symfony\Component\Uid\Uuid;

readonly class SynchronizationPayload
{
    protected function __construct(
        private string $userId,
        private string $id,
        private string $model,
        private string $queue,
        private string $action,
        private array $payload,
    ) {

    }

    public static function from(array $payload): SynchronizationPayload
    {
        return new self(
            $payload['userId'],
            $payload['id'],
            $payload['model'],
            $payload['queue'],
            $payload['action'],
            $payload['payload'],
        );
    }

    public function getUserId(): Uuid
    {
        return Uuid::fromString($this->userId);
    }

    public function getId(): Uuid
    {
        return Uuid::fromString($this->id);
    }

    public function getQueue(): string
    {
        return $this->queue;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getModel(): string
    {
        return $this->model;
    }
}
