<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Entity\Client;
use App\Entity\User;
use App\Repository\ClientRepository;
use Symfony\Component\Uid\Uuid;

readonly class Creator
{
    public function __construct(
        private ClientRepository $clientRepository,
    ) {
    }

    public function __invoke(
        User $owner,
        Uuid $clientId,
        string $name,
        \DateTimeImmutable $createdAt,
        ?string $phone,
        ?string $email,
        ?string $avatar,
    ): void {
        $client = (Client::from($owner, $clientId, $name, $createdAt));

        $client
            ->setEmail($email)
            ->setPhone($phone)
            ->setAvatar($avatar);

        $this->clientRepository->save($client, true);
    }
}
