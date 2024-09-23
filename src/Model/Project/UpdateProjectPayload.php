<?php

declare(strict_types=1);

namespace App\Model\Project;

use App\Entity\Project;
use App\Entity\User;
use App\Exception\MissingPayloadFieldException;
use Symfony\Component\Uid\Uuid;

readonly class UpdateProjectPayload
{
    protected function __construct(
        private Uuid $userId,
        private Uuid $projectId,
        private Uuid $clientId,
        private string $name,
        private ?string $description,
        private ?string $dueDate,
        private ?string $avatar,
    ) {
    }

    public static function from(Project $project, array $payload, User $user): self
    {
        if (!isset($payload['name'])) {
            throw new MissingPayloadFieldException('name');
        }

        if (!isset($payload['clientId'])) {
            throw new MissingPayloadFieldException('clientId');
        }

        return new self(
            $user->getId(),
            $project->getId(),
            Uuid::fromString($payload['clientId']),
            $payload['name'],
            !isset($payload['description']) || $payload['description'] === '' ? null : $payload['description'],
            !isset($payload['dueDate']) || $payload['dueDate'] === '' ? null : $payload['dueDate'],
            !isset($payload['avatar']) || $payload['avatar'] === '' ? null : $payload['avatar'],
        );
    }

    public function getUserId(): Uuid
    {
        return $this->userId;
    }

    public function getProjectId(): Uuid
    {
        return $this->projectId;
    }

    public function getClientId(): Uuid
    {
        return $this->clientId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDueDate(): ?string
    {
        return $this->dueDate;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }
}
