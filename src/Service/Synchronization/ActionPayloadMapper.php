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
use Symfony\Component\Uid\Uuid;

class ActionPayloadMapper
{
    public static function map(Uuid $userId, string $resource, string $action, array $data): ActionPayloadInterface
    {
        return match ([$resource, $action]) {
            ['client', 'create'] => CreateClientPayload::from($userId, $data),
            ['client', 'update'] => UpdateClientPayload::from($data),
            ['client', 'delete'] => DeleteClientPayload::from($data),
            ['project', 'create'] => CreateProjectPayload::from($data),
            ['project', 'update'] => UpdateProjectPayload::from($data),
            ['project', 'delete'] => DeleteProjectPayload::from($data),
            ['task', 'create'] => CreateTaskPayload::from($data),
            ['task', 'update'] => UpdateTaskPayload::from($data),
            ['task', 'delete'] => DeleteTaskPayload::from($data),
            default => throw new InvalidPayloadException('Invalid resource or action'),
        };
    }
}
