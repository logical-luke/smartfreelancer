<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Project;
use App\Model\Project\ProjectDTO;

class ProjectDtoFactory
{
    public function __invoke(Project $project): ProjectDto
    {
        return ProjectDto::fromProject(
            $project,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
        );
    }
}
