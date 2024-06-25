<?php

declare(strict_types=1);

namespace App\Service\Synchronization;

use App\Entity\User;
use App\Exception\InvalidPayloadException;
use App\Model\Client\CreateClientPayload;
use App\Model\Client\DeleteClientPayload;
use App\Model\Client\UpdateClientPayload;
use App\Model\Project\CreateProjectPayload;
use App\Model\Project\DeleteProjectPayload;
use App\Model\Project\UpdateProjectPayload;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Model\Task\CreateTaskPayload;
use App\Model\Task\DeleteTaskPayload;
use App\Model\Task\UpdateTaskPayload;
use App\Model\Timer\StartTimerPayload;
use App\Model\Timer\StopTimerPayload;
use App\Model\Timer\UpdateTimerPayload;
use Symfony\Component\Uid\Uuid;

class ActionPayloadMapper
{
    public static function map(Uuid $userId, string $resource, string $action, array $data): ActionPayloadInterface
    {
        return match ([$resource, $action]) {
            ['client', 'create'] => CreateClientPayload::from($userId, $data),
            ['client', 'update'] => UpdateClientPayload::from($userId, $data),
            ['client', 'delete'] => DeleteClientPayload::from($userId,$data),
            ['project', 'create'] => CreateProjectPayload::from($userId, $data),
            ['project', 'update'] => UpdateProjectPayload::from($userId, $data),
            ['project', 'delete'] => DeleteProjectPayload::from($userId, $data),
            ['task', 'create'] => CreateTaskPayload::from($userId,$data),
            ['task', 'update'] => UpdateTaskPayload::from($userId,$data),
            ['task', 'delete'] => DeleteTaskPayload::from($userId,$data),
            ['timer', 'start'] => StartTimerPayload::from($userId, $data),
            ['timer', 'stop'] => StopTimerPayload::from($userId, $data),
            ['timer', 'update'] => UpdateTimerPayload::from($userId, $data),
            default => throw new InvalidPayloadException('Invalid resource or action'),
        };
    }
}
