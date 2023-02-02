<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Model\User\UserDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/me', name: 'app_me_')]
class MeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json(UserDTO::createFromUser($user));
    }
}
