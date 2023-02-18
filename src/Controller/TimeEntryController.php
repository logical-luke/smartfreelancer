<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Model\TimeEntry\TimeEntryDTO;
use App\Repository\TimeEntryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/time-entry', name: 'app_time_entry_')]
class TimeEntryController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(Request $request, TimeEntryRepository $timeEntryRepository): JsonResponse
    {
        $date = $request->query->get('date')
            ? \DateTimeImmutable::createFromFormat('Y-m-d', $request->query->get('date')) : new \DateTimeImmutable();

        /** @var User $user */
        $user = $this->getUser();

        return $this->json(array_map(static function ($timeEntry) {
            return TimeEntryDTO::fromTimeEntry($timeEntry);
        }, $timeEntryRepository->findByEndTime($user, $date)));
    }
}
