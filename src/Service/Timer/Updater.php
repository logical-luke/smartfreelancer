<?php

declare(strict_types=1);

namespace App\Service\Timer;

use App\Entity\Client;
use App\Entity\Project;
use App\Entity\Task;
use App\Entity\Timer;
use App\Repository\TimeEntryRepository;
use App\Repository\TimerRepository;
use DateTimeImmutable;

readonly class Updater
{

    public function __construct(private TimerRepository $timerRepository)
    {
    }

    public function __invoke(
        Timer $timer,
        ?Client $client,
        ?Project $project,
        ?Task $task,
        DateTimeImmutable $startTime,
    ): void {
        $timer
            ->setClient($client)
            ->setProject($project)
            ->setTask($task)
            ->setStartTime($startTime);

        $this->timerRepository->save($timer, true);
    }
}
