<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Entity\Client;
use App\Repository\ClientRepository;

readonly class Updater
{
    public function __construct(
        private readonly ClientRepository $clientRepository,
    ) {
    }

    public function __invoke(
        Client $client,
        string $name,
        ?string $email,
        ?string $phone,
        ?string $avatar,
    ): void {
        $client
            ->setName($name)
            ->setEmail($email)
            ->setPhone($phone)
            ->setAvatar($avatar);

        $this->clientRepository->save($client, true);
    }
}
