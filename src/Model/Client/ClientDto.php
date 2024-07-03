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
        private int $ongoingTasks,
        private int $plannedTasks,
        private int $finishedTasks,
        private int $blockedTasks,
    ) {
    }

    public static function fromClient(
        Client $client,
        int $revenue,
        int $timeWorked,
        int $ongoingTasks,
        int $plannedTasks,
        int $finishedTasks,
        int $blockedTasks,
    ): self {
        return new self(
            $client->getId()?->toRfc4122(),
            $client->getOwner()?->getId()?->toRfc4122(),
            $client->getName(),
            $client->getAvatar(),
            $client->getPhone(),
            $client->getEmail(),
            $client->getCreatedAt()->getTimestamp(),
            0,
            0,
            0,
            0,
            0,
            0,
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
            'ongoingTasks' => $this->ongoingTasks,
            'plannedTasks' => $this->plannedTasks,
            'finishedTasks' => $this->finishedTasks,
            'blockedTasks' => $this->blockedTasks,
        ];
    }
}
