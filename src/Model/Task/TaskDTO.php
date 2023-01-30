<?php

declare(strict_types=1);

namespace App\Model\Task;

use App\Entity\Task;

class TaskDTO
{
    protected function __construct(
        public readonly int $id,
        public readonly ?string $name,
        public readonly int $ownerId,
        public readonly ?string $description,
        public readonly ?int $projectId,
    )
    {
    }

    public static function fromTask(Task $task): self
    {
        return new self(
            $task->getId(),
            $task->getName(),
            $task->getOwner()->getId(),
            $task->getDescription(),
            $task->getProject()?->getId(),
        );
    }
}
