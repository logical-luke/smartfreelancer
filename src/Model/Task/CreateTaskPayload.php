<?php

declare(strict_types=1);

namespace App\Model\Task;

use App\Exception\InvalidPayloadException;
use App\Model\Synchronization\ActionPayloadInterface;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

readonly class CreateTaskPayload implements ActionPayloadInterface
{
    protected function __construct(
        private string $taskId,
        private string $userId,
        private string $name,
        private int $createdAt,
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

        if (!isset($payload['createdAt'])) {
            throw new InvalidPayloadException('Missing created at');
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
            $payload['createdAt'],
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

    public function toArray(): array
    {
        return [
            'userId' => $this->userId,
            'id' => $this->taskId,
            'name' => $this->name,
            'description' => $this->description,
            'clientId' => $this->clientId,
            'projectId' => $this->projectId,
            'parentTaskId' => $this->parentTaskId,
            'createdAt' => $this->createdAt,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return (new DateTimeImmutable())->setTimestamp($this->createdAt);
    }
}
