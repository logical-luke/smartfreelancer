<?php

declare(strict_types=1);

namespace App\Model\Client;

use App\Entity\Client;

readonly class ClientDto implements \JsonSerializable
{
    protected function __construct(
        private string  $id,
        private string  $ownerId,
        private ?string $name,
        private ?string $avatar,
        private ?string $phone,
        private ?string $email,
        private int $createdAt,
        private int $revenue,
        private int $timeWorked,
        private int $timeEstimated,
        private int $todoTasks,
        private int $inProgressTasks,
        private int $blockedTasks,
        private int $completedTasks,
        private bool $internal,
        private int $income,
        private int $expenses,
        private int $invoiced,
        private int $paid,
        private int $incomeEstimated,
    ) {
    }

    public static function fromClient(
        Client $client,
        int $revenue,
        int $timeWorked,
        int $timeEstimated,
        int $todoTasks,
        int $inProgressTasks,
        int $blockedTasks,
        int $completedTasks,
        int $income,
        int $expenses,
        int $invoiced,
        int $paid,
        int $incomeEstimated,
    ): self {
        return new self(
            $client->getId()?->toRfc4122(),
            $client->getOwner()?->getId()?->toRfc4122(),
            $client->getName(),
            $client->getAvatar(),
            $client->getPhone(),
            $client->getEmail(),
            $client->getCreatedAt()->getTimestamp(),
            $revenue,
            $timeWorked,
            $timeEstimated,
            $todoTasks,
            $inProgressTasks,
            $blockedTasks,
            $completedTasks,
            $client->isInternal(),
            $income,
            $expenses,
            $invoiced,
            $paid,
            $incomeEstimated,
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'ownerId' => $this->ownerId,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'phone' => $this->phone,
            'email' => $this->email,
            'createdAt' => $this->createdAt,
            'revenue' => $this->revenue,
            'timeWorked' => $this->timeWorked,
            'timeEstimated' => $this->timeEstimated,
            'todoTasks' => $this->todoTasks,
            'inProgressTasks' => $this->inProgressTasks,
            'blockedTasks' => $this->blockedTasks,
            'completedTasks' => $this->completedTasks,
            'internal' => $this->internal,
            'income' => $this->income,
            'expenses' => $this->expenses,
            'invoiced' => $this->invoiced,
            'paid' => $this->paid,
            'incomeEstimated' => $this->incomeEstimated,
        ];
    }
}
