<?php

declare(strict_types=1);

namespace App\Model\External;

readonly class GoogleUserDTO
{
    public function __construct(public string $email, private string $accessToken, private string $idToken)
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getIdToken(): string
    {
        return $this->idToken;
    }
}
