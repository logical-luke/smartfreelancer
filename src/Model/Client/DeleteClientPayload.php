<?php

declare(strict_types=1);

namespace App\Model\Client;

use App\Model\Synchronization\ActionPayloadInterface;
use Symfony\Component\Uid\Uuid;

readonly class DeleteClientPayload implements ActionPayloadInterface
{
    protected function __construct(
        private string $clientId,
        private string $userId,
    ) {
    }

    public static function from(Uuid $userId, array $payload): self
    {
        if (!isset($payload['id'])) {
            throw new \RuntimeException('Missing client id');
        }

        return new self(
            $payload['id'],
            $userId->toRfc4122(),
        );
    }

    public function getClientId(): Uuid
    {
        return Uuid::fromString($this->clientId);
    }

    public function getUserId(): Uuid
    {
        return Uuid::fromString($this->userId);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->clientId,
            'userId' => $this->userId,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
