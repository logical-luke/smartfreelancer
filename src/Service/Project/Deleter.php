<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Project;

readonly class Deleter
{
    public function __invoke(Project $project): void
    {
    }
}
