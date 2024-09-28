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
        private int $income,
        private int $expenses,
        private int $invoiced,
        private int $paid,
        private int $estimated,
        private int $timeEstimated,
        private int $timeLeft,
        private ?int $dueDate,
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
        int $income,
        int $expenses,
        int $invoiced,
        int $paid,
        int $estimated,
        int $timeEstimated,
        int $timeLeft,
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
            $income,
            $expenses,
            $invoiced,
            $paid,
            $estimated,
            $timeEstimated,
            $timeLeft,
            $project->getDueDate(),
        );
    }

    public function jsonSerialize(): array
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
            'income' => $this->income,
            'expenses' => $this->expenses,
            'invoiced' => $this->invoiced,
            'paid' => $this->paid,
            'estimated' => $this->estimated,
            'timeEstimated' => $this->timeEstimated,
            'timeLeft' => $this->timeLeft,
            'dueDate' => $this->dueDate,
        ];
    }
}