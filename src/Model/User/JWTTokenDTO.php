<?php

declare(strict_types=1);

namespace App\Model\User;

class JWTTokenDTO
{
    public function __construct(
        public string $token,
        public string $refreshToken,
    )
    {
    }
}
