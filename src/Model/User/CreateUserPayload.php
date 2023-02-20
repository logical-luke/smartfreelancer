<?php

declare(strict_types=1);

namespace App\Model\User;

readonly class CreateUserPayload
{
    protected function __construct(
        private string $email,
        private ?string $password,
        private ?string $accessToken,
        private ?string $refreshToken,
        private LoginTypeEnum $loginType,
    ) {
    }

    public static function from(array $payload): self
    {
        if (!isset($payload['email'])) {
            throw new \InvalidArgumentException('Missing "email" in payload');
        }

        return new self(
            $payload['email'],
            $payload['password'] ?? null,
            $payload['accessToken'] ?? null,
            $payload['refreshToken'] ?? null,
            $payload['loginType'] ?? LoginTypeEnum::EMAIL,
        );
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function getLoginType(): ?LoginTypeEnum
    {
        return $this->loginType;
    }
}
