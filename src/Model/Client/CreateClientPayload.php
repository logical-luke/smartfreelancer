<?php

declare(strict_types=1);

namespace App\Model\Client;

use App\Exception\InvalidPayloadException;
use App\Model\Synchronization\ActionPayloadInterface;
use Symfony\Component\Uid\Uuid;

readonly class CreateClientPayload implements ActionPayloadInterface
{
    protected function __construct(
        private string $userId,
        private string $name,
        private ?string $email,
        private ?string $phone,
        private ?string $avatar,
    ) {
    }

    public static function from(Uuid $userId, array $payload): self
    {
        if (!isset($payload['name'])) {
            throw new InvalidPayloadException('Missing name');
        }

        $payload = array_merge([
            'email' => null,
            'phone' => null,
            'avatar' => null,
        ], $payload);

        return new self(
            $userId->toRfc4122(),
            $payload['name'],
            $payload['email'],
            $payload['phone'],
            $payload['avatar']
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
