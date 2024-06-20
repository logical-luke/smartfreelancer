<?php

namespace App\Entity;

use App\Model\Synchronization\Payload;
use App\Model\Synchronization\SynchronizationStatusEnum;
use App\Repository\SynchronizationLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SynchronizationLogRepository::class)]
class SynchronizationLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BINARY)]
    private Uuid $requestId;

    #[ORM\Column]
    private array $payload = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resource = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $action = null;

    #[ORM\ManyToOne(inversedBy: 'synchronizationLogs')]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $message = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    public function __construct(Uuid $requestId, array $payload, ?string $resource, ?string $action, ?User $user)
    {
        $this->requestId = $requestId;
        $this->payload = $payload;
        $this->resource = $resource;
        $this->action = $action;
        $this->user = $user;
        $this->status = SynchronizationStatusEnum::NEW->value;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestId(): Uuid
    {
        return $this->requestId;
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
        );
    }
}
