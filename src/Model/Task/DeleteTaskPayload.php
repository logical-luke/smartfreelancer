<?php

declare(strict_types=1);

namespace App\Model\Task;

use Symfony\Component\Uid\Uuid;

class DeleteTaskPayload
{
    protected function __construct(private readonly string $id)
    {
    }

    public static function from(array $payload): self
    {
        return new self($payload['id']);
    }

    public function getId(): Uuid
    {
        return Uuid::fromString($this->id);
    }
}
