<?php

declare(strict_types=1);

namespace App\Service\Synchronization;

use App\Entity\User;
use App\Model\Synchronization\ActionPayloadInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.synchronization_processor')]
interface ProcessorInterface
{
    public function sync(User $user, ActionPayloadInterface $payload): void;
}
