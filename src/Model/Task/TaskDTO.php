<?php

declare(strict_types=1);

namespace App\Model\Task;

use App\Entity\Task;

class TaskDTO
{
    protected function __construct(
        public readonly string $id,
        public readonly string $ownerId,
        public readonly ?string $name,
        public readonly ?string $description,
        public readonly ?string $projectId,
    ) {
    }

    public static function fromTask(Task $task): self
    {
        return new self(
            $task->getId()?->toRfc4122(),
            $task->getOwner()?->getId()?->toRfc4122(),
            $task->getName(),
            $task->getDescription(),
            $task->getProject()?->getId()?->toRfc4122(),
        );
    }
}
