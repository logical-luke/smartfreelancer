<?php

declare(strict_types=1);

namespace App\Service\Synchronization;

use App\Model\Synchronization\SynchronizationPayload;
use App\Repository\SynchronizationStatusRepository;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;

readonly class SynchronisationProcessor
{

    public function __construct(
        private UserRepository $userRepository,
        private SynchronizationStatusRepository $statusRepository,
        private SynchronizationQueueServiceFactory $queueServiceFactory,
    ) {
    }

    public function __invoke(SynchronizationPayload $payload): void
    {
        $user = $this->userRepository->find($payload->getUserId());

        if (!$user) {
            throw new \RuntimeException('Invalid user');
        }

        $queueClassname = sprintf('App\Service\%s\%s', $payload->getModel(), $payload->getQueue());

        if (!class_exists($queueClassname)) {
            throw new \RuntimeException("Invalid action class {$queueClassname}");
        }

        $queue = ($this->queueServiceFactory)($queueClassname);

        $actionPayloadClassname = sprintf('App\Model\%s\%sPayload', $payload->getModel(), $payload->getAction());

        if (!class_exists($actionPayloadClassname)) {
            throw new \RuntimeException("Invalid action payload class {$actionPayloadClassname}");
        }

        $actionPayload = forward_static_call([$actionPayloadClassname, "from"], $payload->getPayload());


        $queue->__invoke($actionPayload);

        $synchronizationStatus = $user
            ->getSynchronizationStatus()
            ->setTime(new \DateTimeImmutable());

        $this->statusRepository->save($synchronizationStatus, true);
    }
}
