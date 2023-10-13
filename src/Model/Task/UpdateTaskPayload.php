<?php

declare(strict_types=1);

namespace App\Model\Task;

use Symfony\Component\Uid\Uuid;

readonly class UpdateTaskPayload
{
    protected function __construct(
        private string  $id,
        private ?string $name,
        private ?string $description,
        private ?string $projectId,
        private ?string $taskId,
    )
    {
    }

    public function getTaskId(): ?string
    {
        return $this->taskId;
    }

    public static function from(array $payload): self
    {
        // todo Add validation here

        return new self(
            $payload['id'],
            $payload['name'] ?? null,
            $payload['description'] ?? null,
            $payload['projectId'] ?? null,
            $payload['taskId'] ?? null,
        );
    }

    public function getId(): Uuid
    {
        return Uuid::fromString($this->id);
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
}
