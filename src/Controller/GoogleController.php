<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\User\CreateUserPayload;
use App\Model\User\LoginTypeEnum;
use App\Repository\UserRepository;
use App\Service\External\GoogleClient;
use App\Service\User\JWTTokenGetter;
use App\Service\User\UserCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/google/', name: 'app_google_')]
class GoogleController extends AbstractController
{
    #[Route('connect', name: 'connect_start', methods: 'POST')]
    public function connect(Request $request, GoogleClient $client): JsonResponse
    {
        $csrfToken = bin2hex(random_bytes(16));
        $request->getSession()->set('google_csrf_token', $csrfToken);

        $client
            ->setScopes(['openid', 'email'])
            ->setState($csrfToken);

        return $this->json(['targetUrl' => $client->createAuthUrl()]);
    }

    #[Route('connect/check', name: 'connect_check')]
    public function connectCheckAction(
        JWTTokenGetter $tokenGetter,
        UserCreator $userCreator,
        UserRepository $userRepository,
        GoogleClient $client,
        Request $request
    ): JsonResponse {
        if (!$code = $request->query->get('code')) {
            return $this->json(['error' => 'missing_code'], 400);
        }

        $googleUser = $client->authorize($code);

        if (!$user = $userRepository->findOneByEmail($googleUser->getEmail())) {
            $user = $userCreator(CreateUserPayload::from([
                'email' => $googleUser->getEmail(),
                'loginType' => LoginTypeEnum::GOOGLE,
            ]));
        }

        $tokens = $tokenGetter($user);

        return $this->json(['token' => $tokens->token, 'refreshToken' => $tokens->refreshToken]);
    }
}
