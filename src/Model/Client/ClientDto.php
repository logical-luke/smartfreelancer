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
        private int $todoTasks,
        private int $inProgressTasks,
        private int $blockedTasks,
        private int $completedTasks,
        private bool $internal,
    ) {
    }

    public static function fromClient(
        Client $client,
        int $revenue,
        int $timeWorked,
        int $todoTasks,
        int $inProgressTasks,
        int $blockedTasks,
        int $completedTasks,
        bool $internal,
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
            $todoTasks,
            $inProgressTasks,
            $blockedTasks,
            $completedTasks,
            $client->isInternal(),
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
            'todoTasks' => $this->todoTasks,
            'inProgressTasks' => $this->inProgressTasks,
            'blockedTasks' => $this->blockedTasks,
            'completedTasks' => $this->completedTasks,
            'internal' => $this->internal,
        ];
    }
}
