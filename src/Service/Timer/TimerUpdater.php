<?php

declare(strict_types=1);

namespace App\Service\Timer;

use App\Entity\Timer;
use App\Model\UpdateTimerPayload;
use App\Repository\ProjectRepository;
use App\Repository\TimerRepository;

class TimerUpdater
{
    public function __construct(
        private readonly TimerRepository $timerRepository,
        private readonly ProjectRepository $projectRepository,
    ) {
    }

    public function __invoke(UpdateTimerPayload $payload): Timer
    {
        if (!$timer = $this->timerRepository->find($payload->getId())) {
            throw new \RuntimeException('Timer not found');
        }

        // todo Add check if user is eligible to update timer

        if (
            ($projectId = $payload->getProjectId())
            && ($project = $this->projectRepository->find($projectId))
        ) {
            $timer->setProject($project);
        }

        if (!$payload->getProjectId()) {
            $timer->setProject(null);
        }

        $this->timerRepository->flush();

        return $timer;
    }
}
