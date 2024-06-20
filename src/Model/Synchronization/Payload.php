<?php

declare(strict_types=1);

namespace App\Model\Synchronization;

use App\Entity\User;
use App\Exception\InvalidPayloadException;
use App\Model\Client\CreateClientPayload;
use App\Model\Client\DeleteClientPayload;
use App\Model\Client\UpdateClientPayload;
use App\Model\Project\CreateProjectPayload;
use App\Model\Project\DeleteProjectPayload;
use App\Model\Project\UpdateProjectPayload;
use App\Model\Task\CreateTaskPayload;
use App\Model\Task\DeleteTaskPayload;
use App\Model\Task\UpdateTaskPayload;
use App\Service\Synchronization\ActionPayloadMapper;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

readonly class Payload implements \JsonSerializable
{
    protected function __construct(
        private string $id,
        private string $userId,
        private string $resource,
        private string $action,
        private ActionPayloadInterface $data,
    ) {
    }

    public static function from(
        string $id,
        Uuid $userId,
        string $resource,
        string $action,
        ActionPayloadInterface $actionPayload
    ): Payload {
        return new self(
            $id,
            $userId->toRfc4122(),
            $resource,
            $action,
            $actionPayload,
        );
    }

    /**
     * @throws JsonException
     */
    public static function fromUserRequest(User $user, Request $request): Payload
    {
        $content = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (!isset($content['id'])) {
            throw new InvalidPayloadException('Missing synchronization id');
        }

        if (!isset($content['resource'])) {
            throw new InvalidPayloadException('Missing resource');
        }

        if (!isset($content['action'])) {
            throw new InvalidPayloadException('Missing action');
        }

        if (!isset($content['data'])) {
            throw new InvalidPayloadException('Missing data');
        }

        return self::from(
            $content['id'],
            $user->getId(),
            $content['resource'],
            $content['action'],
            ActionPayloadMapper::map($user->getId(), $content['resource'], $content['action'], $content['data']),
        );
    }

    /**
     * @throws JsonException
     */
    public static function fromJson(string $json): self
    {
        $object = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return new self(
            $object['id'],
            $object['userId'],
            $object['resource'],
            $object['action'],
            ActionPayloadMapper::map(
                Uuid::fromString($object['userId']),
                $object['resource'],
                $object['action'],
                json_decode($object['data'], true, 512, JSON_THROW_ON_ERROR),
            ),
        );
    }

    public function getUserId(): Uuid
    {
        return Uuid::fromString($this->userId);
    }

    public function getId(): Uuid
    {
        return Uuid::fromString($this->id);
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getData(): ActionPayloadInterface
    {
        return $this->data;
    }

    public function getProcessorKey(): string
    {
        return sprintf('%s.%s', $this->getAction(), $this->getResource());
    }

    /**
     * @return array{id: string, userId: string, resource: string, action: string, data: false|string}
     * @throws JsonException
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'resource' => $this->resource,
            'action' => $this->action,
            'data' => json_encode($this->data, JSON_THROW_ON_ERROR),
        ];
    }
}
