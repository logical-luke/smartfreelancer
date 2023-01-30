<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Model\User\UserDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'app_user_')]
class UserController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json(UserDTO::createFromUser($user));
    }
}
