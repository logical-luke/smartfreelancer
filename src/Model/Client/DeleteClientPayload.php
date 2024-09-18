<?php

declare(strict_types=1);

namespace App\Model\Client;

use App\Entity\Client;
use App\Entity\User;
use Symfony\Component\Uid\Uuid;

readonly class DeleteClientPayload
{

    protected function __construct(
        private Uuid $clientId,
        private Uuid $userId
    ) {
    }

    public static function from(Client $client, User $user): self
    {
        return new self(
            $client->getId(),
            $user->getId()
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
}
