<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Entity\Client;
use App\Model\Client\ClientDto;

class ClientDtoFactory
{
    public function __invoke(Client $client): ClientDto
    {
        return ClientDto::fromClient($client, 0, 0, 0, 0, 0, 0, $client->isInternal());
    }
}
