<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\User;

class UserDTO
{
    protected function __construct(
        public int $id,
        public string $email,
        public ?string $name,
    ) {
    }

    public static function createFromUser(User $user): self
    {
        return new self($user->getId(), $user->getEmail(), $user->getName());
    }
}
