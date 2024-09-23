<?php

declare(strict_types=1);

namespace App\Model\Project;

use App\Entity\Project;

readonly class ProjectDTO implements \JsonSerializable
{
    protected function __construct(
        private string $id,
        private string $ownerId,
        private string $clientId,
        private ?string $name,
        private ?string $description,
        private ?string $avatar,
        private int $createdAt,
        private int $revenue,
        private int $timeWorked,
        private int $todoTasks,
        private int $inProgressTasks,
        private int $blockedTasks,
        private int $completedTasks,
    ) {
    }

    public static function fromProject(
        Project $project,
        int $revenue,
        int $timeWorked,
        int $todoTasks,
        int $inProgressTasks,
        int $blockedTasks,
        int $completedTasks,
    ): self
    {
        return new self(
            $project->getId()?->toRfc4122(),
            $project->getOwner()?->getId()?->toRfc4122(),
            $project->getClient()?->getId()?->toRfc4122(),
            $project->getName(),
            $project->getDescription(),
            $project->getAvatar(),
            $project->getCreatedAt()->getTimestamp(),
            $revenue,
            $timeWorked,
            $todoTasks,
            $inProgressTasks,
            $blockedTasks,
            $completedTasks,
        );
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'ownerId' => $this->ownerId,
            'clientId' => $this->clientId,
            'name' => $this->name,
            'description' => $this->description,
            'avatar' => $this->avatar,
            'createdAt' => $this->createdAt,
            'revenue' => $this->revenue,
            'timeWorked' => $this->timeWorked,
            'todoTasks' => $this->todoTasks,
            'inProgressTasks' => $this->inProgressTasks,
            'blockedTasks' => $this->blockedTasks,
            'completedTasks' => $this->completedTasks,
        ];
    }
}
