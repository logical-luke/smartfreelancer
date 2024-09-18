<?php

declare(strict_types=1);

namespace App\Model\Client;

use App\Entity\User;
use App\Exception\MissingPayloadFieldException;
use Symfony\Component\Uid\Uuid;

readonly class CreateClientPayload
{
    protected function __construct(
        private Uuid $ownerId,
        private string $name,
        private ?string $phone,
        private ?string $email,
        private ?string $avatarPath,
    ) {
    }

    public static function from(User $owner, array $payload): self
    {
        if (!isset($payload['name'])) {
            throw new MissingPayloadFieldException('name');
        }

//        todo: validate phone, email, avatarPath

        return new self(
            $owner->getId(),
            $payload['name'],
            !isset($payload['phone']) || $payload['phone'] === '' ? null : $payload['phone'],
            !isset($payload['email']) || $payload['email'] === '' ? null : $payload['email'],
            !isset($payload['avatar']) || $payload['avatar'] === '' ? null : $payload['avatar'],
        );
    }

    public function getOwnerId(): Uuid
    {
        return $this->ownerId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getAvatarPath(): ?string
    {
        return $this->avatarPath;
    }
}
