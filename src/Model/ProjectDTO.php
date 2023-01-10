<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Project;

class ProjectDTO
{
    protected function __construct(public readonly int $id, public readonly ?string $name, public readonly int $ownerId)
    {
    }

    public static function fromProject(Project $project): self
    {
        return new self(
            $project->getId(),
            $project->getName(),
            $project->getOwner()->getId()
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'ownerId' => $this->ownerId,
        ];
    }
}
