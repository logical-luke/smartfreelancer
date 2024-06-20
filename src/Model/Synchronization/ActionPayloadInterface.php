<?php

declare(strict_types=1);

namespace App\Model\Synchronization;

interface ActionPayloadInterface extends \JsonSerializable
{
    /**
     * @return array{string: mixed}
     */
    public function toArray(): array;
}
