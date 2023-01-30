<?php

declare(strict_types=1);

namespace App\Service\Timer;

use App\Entity\Timer;
use App\Model\UpdateTimerPayload;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Repository\TimerRepository;

class TimerUpdater
{
    public function __construct(
        private readonly TimerRepository $timerRepository,
        private readonly ProjectRepository $projectRepository,
        private readonly ClientRepository $clientRepository,
    ) {
    }

    public function __invoke(UpdateTimerPayload $payload): Timer
    {
        if (!$timer = $this->timerRepository->find($payload->getId())) {
            throw new \RuntimeException('Timer not found');
        }

        // todo Add check if user is eligible to update timer

        $timer->setProject(null);
        if (
            ($projectId = $payload->getProjectId())
            && ($project = $this->projectRepository->find($projectId))
        ) {
            $timer->setProject($project);
        }

        $timer->setClient(null);

        if (
            ($clientId = $payload->getClientId())
            && ($client = $this->clientRepository->find($clientId))
        ) {
            $timer->setClient($client);
        }

        $this->timerRepository->flush();

        return $timer;
    }
}
