<?php

namespace App\Entity;

use App\Repository\SynchronizationLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SynchronizationLogRepository::class)]
class SynchronizationLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BINARY)]
    private $requestId = null;

    #[ORM\Column]
    private array $payload = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $queue = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $action = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $model = null;

    #[ORM\ManyToOne(inversedBy: 'synchronizationLogs')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestId()
    {
        return $this->requestId;
    }

    public function setRequestId($requestId): self
    {
        $this->requestId = $requestId;

        return $this;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function setPayload(array $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    public function getQueue(): ?string
    {
        return $this->queue;
    }

    public function setQueue(?string $queue): self
    {
        $this->queue = $queue;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
