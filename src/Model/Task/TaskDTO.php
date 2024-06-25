<?php

declare(strict_types=1);

namespace App\Model\Task;

use App\Entity\Task;

readonly class TaskDTO
{
    protected function __construct(
        public string $id,
        public string $ownerId,
        public string $name,
        public ?string $description,
        public ?string $projectId,
        public ?string $clientId,
        public ?string $taskId,
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
            $task->getClient()?->getId()?->toRfc4122(),
            $task->getTask()?->getId()?->toRfc4122(),
        );
    }
}
