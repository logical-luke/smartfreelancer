<?php

declare(strict_types=1);

namespace App\Model\Client;

use App\Exception\InvalidPayloadException;
use App\Model\Synchronization\ActionPayloadInterface;
use Symfony\Component\Uid\Uuid;

readonly class CreateClientPayload implements ActionPayloadInterface
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

        if ($payload['email'] && !filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidPayloadException('Invalid email');
        }

        if ($payload['phone'] && !preg_match(pattern: '/^\+?[0-9]{1,4}[0-9]{6,14}$/', subject: $payload['phone'])) {
            throw new InvalidPayloadException('Invalid phone number');
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public
    function getUserId(): Uuid
    {
        return Uuid::fromString($this->userId);
    }

    public
    function getName(): ?string
    {
        return $this->name;
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
            'userId' => $this->userId,
            'id' => $this->clientId,
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

    public function getClientId(): Uuid
    {
        return Uuid::fromString($this->clientId);
    }
}
