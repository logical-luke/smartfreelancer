<?php

declare(strict_types=1);

namespace App\Model\Task;

use App\Exception\InvalidPayloadException;
use Symfony\Component\Uid\Uuid;

readonly class UpdateTaskPayload
{
    protected function __construct(
        private string $taskId,
        private string $userId,
        private string $name,
        private ?string $description,
        private ?string $clientId,
        private ?string $projectId,
        private ?string $parentTaskId,
    )
    {
    }

    public static function from(Uuid $userId, array $payload): self
    {
        if (!isset($payload['id'])) {
            throw new InvalidPayloadException('Missing task id');
        }

        if (!isset($payload['name'])) {
            throw new InvalidPayloadException('Missing name');
        }

        $payload = array_merge([
            'description' => null,
            'clientId' => null,
            'projectId' => null,
            'parentTaskId' => null,
        ], $payload);

        return new self(
            $payload['id'],
            $userId->toRfc4122(),
            $payload['name'],
            $payload['description'],
            $payload['clientId'],
            $payload['projectId'],
            $payload['parentTaskId'],
        );
    }

    public function getTaskId(): ?string
    {
        return $this->taskId;
    }

    public function getId(): Uuid
    {
        return Uuid::fromString($this->taskId);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getProjectId(): ?Uuid
    {
        return $this->projectId ? Uuid::fromString($this->projectId) : null;
    }

    public function getClientId(): ?Uuid
    {
        return $this->clientId ? Uuid::fromString($this->clientId) : null;
    }

    public function getParentTaskId(): ?Uuid
    {
        return $this->parentTaskId ? Uuid::fromString($this->parentTaskId) : null;
    }

    public function getUserId(): Uuid
    {
        return Uuid::fromString($this->userId);
    }
}
