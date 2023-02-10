<?php

declare(strict_types=1);

namespace App\Model\User;

class RegistrationPayload
{
    protected function __construct(
        private readonly string $email,
        private readonly string $password,
        private readonly string $confirmPassword,
    ) {
    }

    public static function from(array $array): self
    {
        return new self($array['email'], $array['password'], $array['confirmPassword']);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getConfirmPassword(): string
    {
        return $this->confirmPassword;
    }
}
