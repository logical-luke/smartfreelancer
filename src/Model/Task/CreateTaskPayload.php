<?php

declare(strict_types=1);

namespace App\Model\Task;

use App\Exception\InvalidPayloadException;

class CreateTaskPayload
{
    protected function __construct(
        private readonly int $ownerId,
        private readonly ?string $name,
        private readonly ?string $description,
        private readonly ?int $projectId,
    ) {
    }

    public static function from(array $payload): self
    {
        if (!isset($payload['ownerId'])) {
            throw new InvalidPayloadException('Missing owner id');
        }

        if (!isset($payload['name'])) {
            throw new InvalidPayloadException('Missing name');
        }

        $payload = array_merge([
            'name' => null,
            'description' => null,
            'ownerId' => null,
            'projectId' => null,
        ], $payload);

        return new self($payload['ownerId'], $payload['name'], $payload['description'], $payload['projectId']);
    }

    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getProjectId()
    {
        return $this->projectId;
    }
}
