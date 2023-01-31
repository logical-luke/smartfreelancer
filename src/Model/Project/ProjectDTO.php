<?php

declare(strict_types=1);

namespace App\Model\Project;

use App\Entity\Project;

class ProjectDTO
{
    protected function __construct(
        public readonly string $id,
        public readonly string $ownerId,
        public readonly ?string $name,
        public readonly ?string $description,
        public readonly ?string $clientId,
    ) {
    }

    public static function fromProject(Project $project): self
    {
        return new self(
            $project->getId()?->toRfc4122(),
            $project->getOwner()->getId()?->toRfc4122(),
            $project->getName(),
            $project->getDescription(),
            $project->getClient()?->getId()?->toRfc4122(),
        );
    }
}
