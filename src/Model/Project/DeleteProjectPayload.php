<?php

declare(strict_types=1);

namespace App\Model\Project;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Component\Uid\Uuid;

readonly class DeleteProjectPayload
{

    protected function __construct(
        private Uuid $projectId,
        private Uuid $userId
    ) {
    }

    public static function from(Project $project, User $user): self
    {
        return new self(
            $project->getId(),
            $user->getId()
        );
    }

    public function getProjectId(): Uuid
    {
        return $this->projectId;
    }

    public function getUserId(): Uuid
    {
        return $this->userId;
    }
}
