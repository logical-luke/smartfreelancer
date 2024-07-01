<?php

namespace App\Entity;

use App\Repository\TimerRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TimerRepository::class)]
class Timer
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private Uuid $id;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private DateTimeImmutable $startTime;

    #[ORM\OneToOne(inversedBy: 'timer', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private User $owner;

    #[ORM\OneToOne(inversedBy: 'timer', cascade: ['persist'])]
    private ?Client $client = null;

    #[ORM\OneToOne(inversedBy: 'timer', cascade: ['persist'])]
    private ?Project $project = null;

    #[ORM\OneToOne(inversedBy: 'timer', cascade: ['persist'])]
    private ?Task $task = null;

    protected function __construct(User $owner, Uuid $id)
    {
        $this->id = $id;
        $this->owner = $owner;
        $this->startTime = new DateTimeImmutable();
    }

    public static function fromUser(User $owner, Uuid $id): self
    {
        return new self($owner, $id);
    }

    public function getStartTime(): DateTimeImmutable
    {
        return $this->startTime;
    }

    public function setStartTime(DateTimeImmutable $startTime): Timer
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }
}
