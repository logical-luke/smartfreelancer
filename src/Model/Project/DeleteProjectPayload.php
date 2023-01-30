<?php

declare(strict_types=1);

namespace App\Model\Project;

use Symfony\Component\Uid\Uuid;

class DeleteProjectPayload
{
    protected function __construct(private readonly string $id)
    {
    }

    public static function from(array $payload): self
    {
        // todo Add validation here

        return new self($payload['id']);
    }

    public function getId(): Uuid
    {
        return Uuid::fromString($this->id);
    }
}
