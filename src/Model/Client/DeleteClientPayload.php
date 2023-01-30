<?php

declare(strict_types=1);

namespace App\Model\Client;

class DeleteClientPayload
{
    protected function __construct(private readonly int $id)
    {
    }

    public static function from(array $payload): self
    {
        // todo Add validation here

        return new self($payload['id']);
    }

    public function getId(): int
    {
        return $this->id;
    }
}
