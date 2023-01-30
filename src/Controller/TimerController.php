<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\InvalidPayloadException;
use App\Model\Timer\CreateTimerPayload;
use App\Model\Timer\StopTimerPayload;
use App\Model\Timer\TimerDTO;
use App\Model\Timer\UpdateTimerPayload;
use App\Repository\TimerRepository;
use App\Service\Timer\TimerCreator;
use App\Service\Timer\TimerStopper;
use App\Service\Timer\TimerUpdater;
use JsonException;
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
        if (!$timer = $timerRepository->findOneByUser($this->getUser())) {
            return $this->json([], Response::HTTP_OK);
        }

        return $this->json(TimerDTO::fromTimer($timer));
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, TimerRepository $timerRepository, TimerCreator $timerCreator): JsonResponse
    {
        if ($timer = $timerRepository->findOneByUser($this->getUser())) {
            return $this->json([], Response::HTTP_CONFLICT);
        }

        try {
            $payload = array_merge([
                'ownerId' => $this->getUser()->getId(),
            ], json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (JsonException $e) {
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
    public function stop(TimerRepository $timerRepository, TimerStopper $timerStopper): JsonResponse
    {
        if (!$timer = $timerRepository->findOneByUser($this->getUser())) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        try {
            $timerStopper(StopTimerPayload::from([
                'timerId' => $timer->getId(),
            ]));
        } catch (InvalidPayloadException $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([], Response::HTTP_OK);
    }

    #[Route('/update/{id}', name: 'update', methods: 'POST')]
    public function update(int $id, TimerUpdater $timerUpdater, Request $request): JsonResponse
    {
        try {
            $payload = array_merge([
                'id' => $id,
            ], json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (JsonException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        $timer = $timerUpdater(UpdateTimerPayload::from($payload));

        return $this->json(
            TimerDTO::fromTimer($timer)
        );
    }
}
