<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Exception\InvalidPayloadException;
use App\Model\TimeEntry\TimeEntryDTO;
use App\Model\Timer\CreateTimerPayload;
use App\Model\Timer\StopTimerPayload;
use App\Model\Timer\TimerDTO;
use App\Model\Timer\UpdateTimerPayload;
use App\Repository\TimerRepository;
use App\Service\Timer\TimerCreator;
use App\Service\Timer\TimerStopper;
use App\Service\Timer\TimerUpdater;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/timer', name: 'app_timer_')]
class TimerController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(TimerRepository $timerRepository): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$timer = $timerRepository->findOneByUser($user)) {
            return $this->json([], Response::HTTP_OK);
        }

        return $this->json(TimerDTO::fromTimer($timer));
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, TimerRepository $timerRepository, TimerCreator $timerCreator): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($timerRepository->findOneByUser($user)) {
            return $this->json([], Response::HTTP_CONFLICT);
        }

        try {
            $payload = array_merge([
                'ownerId' => $user->getId()?->toRfc4122(),
            ], json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (\JsonException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        try {
            $timer = $timerCreator(CreateTimerPayload::from($payload));
        } catch (InvalidPayloadException $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(TimerDTO::fromTimer($timer));
    }

    #[Route('/stop', name: 'stop')]
    public function stop(TimerRepository $timerRepository, Request $request, TimerStopper $timerStopper): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$timer = $timerRepository->findOneByUser($user)) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        try {
            $payload = array_merge([
                'timerId' => $timer->getId()?->toRfc4122(),
                'ownerId' => $user->getId()?->toRfc4122(),
            ], json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
            $timeEntry = $timerStopper(StopTimerPayload::from($payload));
        } catch (InvalidPayloadException $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(TimeEntryDTO::fromTimeEntry($timeEntry), Response::HTTP_OK);
    }

    #[Route('/update/{id}', name: 'update', methods: 'POST')]
    public function update(string $id, TimerUpdater $timerUpdater, Request $request): JsonResponse
    {
        try {
            $payload = array_merge([
                'id' => $id,
            ], json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (\JsonException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        $timer = $timerUpdater(UpdateTimerPayload::from($payload));

        return $this->json(
            TimerDTO::fromTimer($timer)
        );
    }
}
