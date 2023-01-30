<?php

declare(strict_types=1);

namespace App\Service\Timer;

use App\Entity\Timer;
use App\Exception\InvalidPayloadException;
use App\Model\CreateTimerPayload;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Repository\TimerRepository;
use App\Repository\UserRepository;
use App\Validator\Timestamp;
use DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TimerCreator
{
    public function __construct(
        private readonly TimerRepository $timerRepository,
        private readonly UserRepository $userRepository,
        private readonly ProjectRepository $projectRepository,
        private readonly ClientRepository $clientRepository,
    ) {
    }

    public function __invoke(CreateTimerPayload $payload): Timer
    {
        if (!$user = $this->userRepository->find($payload->getOwnerId())) {
            throw new InvalidPayloadException('Invalid owner id');
        }

        $timer = Timer::fromUser($user);

        if ($projectId = $payload->getProjectId()) {
            if (!$project = $this->projectRepository->find($projectId)) {
                throw new InvalidPayloadException('Invalid project id');
            }
            $timer->setProject($project);
        }

        if ($clientId = $payload->getClientId()) {
            if (!$client = $this->clientRepository->find($clientId)) {
                throw new InvalidPayloadException('Invalid client id');
            }
            $timer->setClient($client);
        }



        $this->timerRepository->persist($timer);
        $this->timerRepository->flush();

        return $timer;
    }
}
