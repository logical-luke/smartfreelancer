<?php

namespace App\Entity;

use App\Model\Synchronization\Payload;
use App\Model\Synchronization\SynchronizationStatusEnum;
use App\Repository\SynchronizationLogRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SynchronizationLogRepository::class)]
class SynchronizationLog
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private Uuid $id;

    #[ORM\Column]
    private array $payload;

    #[ORM\Column(length: 255)]
    private string $resource;

    #[ORM\Column(length: 255)]
    private string $action;

    #[ORM\ManyToOne(inversedBy: 'synchronizationLogs')]
    private User $user;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $message = null;

    #[ORM\Column(length: 255)]
    private string $status;

    #[ORM\Column]
    private DateTimeImmutable $requestedAt;

    #[ORM\Column]
    private DateTimeImmutable $receivedAt;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $finishedAt = null;

    public function __construct(Uuid $id, array $payload, string $resource, string $action, User $user, DateTimeImmutable $requestedAt)
    {
        $this->id = $id;
        $this->payload = $payload;
        $this->resource = $resource;
        $this->action = $action;
        $this->user = $user;
        $this->requestedAt = $requestedAt;
        $this->receivedAt = new DateTimeImmutable();
        $this->status = SynchronizationStatusEnum::NEW->value;
    }


    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public static function fromPayload(User $user, Payload $payload): self
    {
        return new self(
            $payload->getId(),
            $payload->getData()->toArray(),
            $payload->getResource(),
            $payload->getAction(),
            $user,
            $payload->getRequestedAt(),
        );
    }

    public function getRequestedAt(): ?DateTimeImmutable
    {
        return $this->requestedAt;
    }

    public function setRequestedAt(DateTimeImmutable $requestedAt): static
    {
        $this->requestedAt = $requestedAt;

        return $this;
    }

    public function getReceivedAt(): ?DateTimeImmutable
    {
        return $this->receivedAt;
    }

    public function setReceivedAt(DateTimeImmutable $receivedAt): static
    {
        $this->receivedAt = $receivedAt;

        return $this;
    }

    public function getFinishedAt(): ?DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?DateTimeImmutable $finishedAt): static
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }
}
