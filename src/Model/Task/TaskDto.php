<?php

declare(strict_types=1);

namespace App\Model\Task;

use App\Entity\Task;

readonly class TaskDto
{
    protected function __construct(
        public string $id,
        public string $ownerId,
        public int $createdAt,
        public string $name,
        public ?string $description,
        public ?string $projectId,
        public ?string $clientId,
        public ?string $parentTaskId,
    ) {
    }

    public static function fromTask(Task $task): self
    {
        return new self(
            $task->getId()?->toRfc4122(),
            $task->getOwner()?->getId()?->toRfc4122(),
            $task->getCreatedAt()->getTimestamp(),
            $task->getName(),
            $task->getDescription(),
            $task->getProject()?->getId()?->toRfc4122(),
            $task->getClient()?->getId()?->toRfc4122(),
            $task->getParentTask()?->getId()?->toRfc4122(),
        );
    }
}
