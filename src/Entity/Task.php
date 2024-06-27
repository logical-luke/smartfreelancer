<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private Uuid $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 10000, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private User $owner;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Project $project = null;

    #[ORM\OneToOne(mappedBy: 'task', cascade: ['persist', 'remove'])]
    private ?Timer $timer = null;

    #[ORM\OneToMany(mappedBy: 'task', targetEntity: TimeEntry::class)]
    private Collection $timeEntries;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $scheduledDate = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Client $client = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'subtasks')]
    private ?self $parentTask = null;

    #[ORM\OneToMany(mappedBy: 'parentTask', targetEntity: self::class)]
    private Collection $subtasks;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    protected function __construct(User $user, Uuid $id, string $name, DateTimeImmutable $createdAt)
    {
        $this->id = $id;
        $this->owner = $user;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->timeEntries = new ArrayCollection();
        $this->subtasks = new ArrayCollection();
    }

    public static function fromUser(User $user, Uuid $id, string $name, DateTimeImmutable $createdAt): self
    {
        return new self($user, $id, $name, $createdAt);
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

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

    public function getTimer(): ?Timer
    {
        return $this->timer;
    }

    public function setTimer(?Timer $timer): self
    {
        // unset the owning side of the relation if necessary
        if (null === $timer && null !== $this->timer) {
            $this->timer->setTask(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $timer && $timer->getTask() !== $this) {
            $timer->setTask($this);
        }

        $this->timer = $timer;

        return $this;
    }

    /**
     * @return Collection<int, TimeEntry>
     */
    public function getTimeEntries(): Collection
    {
        return $this->timeEntries;
    }

    public function addTimeEntry(TimeEntry $timeEntry): self
    {
        if (!$this->timeEntries->contains($timeEntry)) {
            $this->timeEntries->add($timeEntry);
            $timeEntry->setTask($this);
        }

        return $this;
    }

    public function removeTimeEntry(TimeEntry $timeEntry): self
    {
        if ($this->timeEntries->removeElement($timeEntry)) {
            // set the owning side to null (unless already changed)
            if ($timeEntry->getTask() === $this) {
                $timeEntry->setTask(null);
            }
        }

        return $this;
    }

    public function getScheduledDate(): ?\DateTimeImmutable
    {
        return $this->scheduledDate;
    }

    public function setScheduledDate(?\DateTimeImmutable $scheduledDate): self
    {
        $this->scheduledDate = $scheduledDate;

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

    public function getParentTask(): ?self
    {
        return $this->parentTask;
    }

    public function setParentTask(?self $parentTask): self
    {
        $this->parentTask = $parentTask;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubtasks(): Collection
    {
        return $this->subtasks;
    }

    public function addSubtask(self $subtask): self
    {
        if (!$this->subtasks->contains($subtask)) {
            $this->subtasks->add($subtask);
            $subtask->setParentTask($this);
        }

        return $this;
    }

    public function removeSubtask(self $subtask): self
    {
        // set the owning side to null (unless already changed)
        if ($this->subtasks->removeElement($subtask) && $subtask->getParentTask() === $this) {
            $subtask->setParentTask(null);
        }

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
