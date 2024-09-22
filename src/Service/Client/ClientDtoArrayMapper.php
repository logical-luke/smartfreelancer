<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Entity\Client;
use App\Model\Client\ClientDto;
use Doctrine\Common\Collections\Collection;

readonly class ClientDtoArrayMapper
{
    public function __construct(
        private ClientDtoFactory $clientDtoFactory
    ) {
    }

    public function map(Collection $clients): array
    {
        $clientsArray = $clients->toArray();

        usort($clientsArray, static function ($a, $b) {
            return $a->getCreatedAt() > $b->getCreatedAt();
        });

        return array_map(function (Client $client) {
            return (($this->clientDtoFactory)($client));
        }, $clientsArray);
    }
}
