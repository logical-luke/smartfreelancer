<?php

declare(strict_types=1);

namespace App\Model\Client;

use App\Entity\User;
use App\Exception\MissingPayloadFieldException;
use Symfony\Component\Uid\Uuid;

readonly class UpdateClientPayload
{
    protected function __construct(
        private Uuid $clientId,
        private Uuid $userId,
        private string $name,
        private ?string $phone,
        private ?string $email,
        private ?string $avatarPath,
    ) {
    }

    public static function from(array $payload, User $user): self
    {
        if (!isset($payload['id'])) {
            throw new MissingPayloadFieldException('id');
        }

        if (!isset($payload['name'])) {
            throw new MissingPayloadFieldException('name');
        }

        return new self(
            Uuid::fromString($payload['id']),
            $user->getId(),
            $payload['name'],
            !isset($payload['phone']) || $payload['phone'] === '' ? null : $payload['phone'],
            !isset($payload['email']) || $payload['email'] === '' ? null : $payload['email'],
            !isset($payload['avatar']) || $payload['avatar'] === '' ? null : $payload['avatar'],
        );
    }

    public function getClientId(): Uuid
    {
        return $this->clientId;
    }

    public function getUserId(): Uuid
    {
        return $this->userId;
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
