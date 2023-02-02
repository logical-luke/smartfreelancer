<?php

declare(strict_types=1);

namespace App\Model\User;

use App\Entity\User;

class MeDTO
{
    protected function __construct(
        public string $id,
        public string $email,
        public ?string $name,
    ) {
    }

    public static function createFromUser(User $user): self
    {
        return new self($user->getId()?->toRfc4122(), $user->getEmail(), $user->getName());
    }
}
