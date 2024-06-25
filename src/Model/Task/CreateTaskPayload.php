<?php

declare(strict_types=1);

namespace App\Model\Task;

use App\Exception\InvalidPayloadException;
use Symfony\Component\Uid\Uuid;

readonly class CreateTaskPayload
{
    protected function __construct(
        private string $ownerId,
        private string $taskId,
        private string $name,
        private ?string $description,
        private ?string $clientId,
        private ?string $projectId,
        private ?string $parentTaskId,
    ) {
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
            $userId->toRfc4122(),
            $payload['id'],
            $payload['name'],
            $payload['description'],
            $payload['clientId'],
            $payload['projectId'],
            $payload['parentTaskId'],
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

    public function getName(): ?string
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
}
