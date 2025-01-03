<?php

declare(strict_types=1);

namespace App\Exception;

class InvalidPayloadException extends \RuntimeException
{
    public function __construct(string $string)
    {
        parent::__construct();

        $this->message = $string;
    }
}
