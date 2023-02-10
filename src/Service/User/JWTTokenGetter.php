<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Model\User\JWTTokenDTO;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class JWTTokenGetter
{
    public function __construct(
        private readonly JWTTokenManagerInterface $tokenManager,
        private readonly RefreshTokenManagerInterface $refreshTokenManager,
        private readonly RefreshTokenGeneratorInterface $refreshTokenGenerator,
    ) {
    }

    public function __invoke(User $user): JWTTokenDTO
    {
        $token = $this->tokenManager->create($user);

        $refreshToken = $this->refreshTokenGenerator->createForUserWithTtl($user, 3600);

        $this->refreshTokenManager->save($refreshToken);

        return new JWTTokenDTO(
            $token,
            $refreshToken->getRefreshToken()
        );
    }
}
