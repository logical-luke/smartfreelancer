<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Project;
use App\Model\Project\ProjectDto;
use App\Service\Project\ProjectDtoFactory;
use Doctrine\Common\Collections\Collection;

readonly class ProjectDtoArrayMapper
{
    public function __construct(
        private ProjectDtoFactory $projectDtoFactory
    ) {
    }

    public function map(Collection $projects): array
    {
        $projectsArray = $projects->toArray();

        usort($projectsArray, static function ($a, $b) {
            return $a->getCreatedAt() > $b->getCreatedAt();
        });

        return array_map(function (Project $project) {
            return (($this->projectDtoFactory)($project));
        }, $projectsArray);
    }
}
