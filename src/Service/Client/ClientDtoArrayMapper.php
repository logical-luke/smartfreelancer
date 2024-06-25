<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Entity\Client;
use App\Model\Client\ClientDto;
use Doctrine\Common\Collections\Collection;
readonly class ClientDtoArrayMapper
{
    public static function map(Collection $clients): array
    {
        $clientsArray = $clients->toArray();

        usort($clientsArray, static function($a, $b) {
            return $a->getCreatedAt() < $b->getCreatedAt();
        });

        return array_map(static function (Client $client) { return ClientDto::fromClient($client); }, $clientsArray);
    }
}
