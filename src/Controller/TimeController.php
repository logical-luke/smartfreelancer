<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Time\TimeDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/time', name: 'app_time_')]
class TimeController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): JsonResponse
    {
        return $this->json(TimeDTO::fromDateTime(new \DateTimeImmutable())->getTime());
    }
}
