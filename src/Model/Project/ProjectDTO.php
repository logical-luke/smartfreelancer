<?php

declare(strict_types=1);

namespace App\Model\Project;

use App\Entity\Project;

readonly class ProjectDTO
{
    protected function __construct(
        public string $id,
        public string $ownerId,
        public ?string $name,
        public ?string $description,
        public ?string $clientId,
    ) {
    }

    public static function fromProject(Project $project): self
    {
        return new self(
            $project->getId()?->toRfc4122(),
            $project->getOwner()?->getId()?->toRfc4122(),
            $project->getName(),
            $project->getDescription(),
            $project->getClient()?->getId()?->toRfc4122(),
        );
    }
}
