<?php

declare(strict_types=1);

namespace App\Model\Client;

use App\Exception\InvalidPayloadException;
use App\Model\Synchronization\ActionPayloadInterface;
use Symfony\Component\Uid\Uuid;

readonly class UpdateClientPayload implements ActionPayloadInterface
{
    protected function __construct(
        private string $clientId,
        private string $userId,
        private string $name,
        private ?string $email,
        private ?string $phone,
        private ?string $avatar,
    ) {
    }

    public static function from(Uuid $userId, array $payload): self
    {
        if (!isset($payload['id'])) {
            throw new InvalidPayloadException('Missing client id');
        }

        if (!isset($payload['name'])) {
            throw new InvalidPayloadException('Missing name');
        }

        $payload = array_merge([
            'email' => null,
            'phone' => null,
            'avatar' => null,
        ], $payload);

        return new self(
            $payload['id'],
            $userId->toRfc4122(),
            $payload['name'],
            $payload['email'],
            $payload['phone'],
            $payload['avatar'],
        );
    }

    public function getClientId(): Uuid
    {
        return Uuid::fromString($this->clientId);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUserId(): Uuid
    {
        return Uuid::fromString($this->userId);
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function toArray(): array
    {
        return [
            'clientId' => $this->clientId,
            'userId' => $this->userId,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
