<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\Entity\User;
use App\Model\Time\TimeDTO;
use App\Model\User\UserDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/me', name: 'app_me_')]
class MeController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json([
            'user' => UserDTO::createFromUser($user),
            'time' => TimeDTO::fromDateTime(new \DateTimeImmutable())->getTime(),
        ]);
    }
}
