<?php

declare(strict_types=1);

namespace App\Service\Synchronization;

use App\Entity\SynchronizationLog;
use App\Model\Synchronization\Payload;
use App\Model\Synchronization\SynchronizationStatusEnum;
use App\Repository\SynchronizationLogRepository;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

readonly class Handler
{
    public function __construct(
        private UserRepository $userRepository,
        private ProcessorsCollection $processorsCollection,
        private SynchronizationLogRepository $synchronizationLogRepository,
    ) {
    }

    public function __invoke(Payload $payload): void
    {
        $user = $this->userRepository->find($payload->getUserId());

        if (!$user) {
            throw new \RuntimeException('Invalid user');
        }

        $synchronizationLog = SynchronizationLog::fromPayload($user, $payload);
        $this->synchronizationLogRepository->save($synchronizationLog, true);

        $processor = $this->processorsCollection->get($payload->getProcessorKey());

        try {
            $synchronizationLog->setStatus(SynchronizationStatusEnum::IN_PROGRESS->value);
            $this->synchronizationLogRepository->save($synchronizationLog, true);
            $processor->sync($payload->getData());
            $synchronizationLog->setStatus(SynchronizationStatusEnum::COMPLETED->value);
            $user->setSynchronizationTime(new \DateTimeImmutable());

            $this->userRepository->save($user, true);
        } catch (\Throwable $exception) {
            $synchronizationLog
                ->setStatus(SynchronizationStatusEnum::FAILED->value)
                ->setMessage($exception->getMessage());
        }

        $this->synchronizationLogRepository->save($synchronizationLog, true);
    }
}
