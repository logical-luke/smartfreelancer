<?php

declare(strict_types=1);

namespace App\Exception;

class InvalidPayloadException extends \RuntimeException
{

    /**
     * @param string $string
     */
    public function __construct(string $string)
    {
        parent::__construct();

        $this->message = 'Invalid payload: '. $string;
    }
}
