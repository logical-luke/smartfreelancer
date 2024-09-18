<?php

declare(strict_types=1);

namespace App\Exception;

use App\Enum\ExceptionErrors;

class ResourceAlreadyExistsException extends \RuntimeException
{
    public function __construct(string $resourceName)
    {
        parent::__construct(sprintf(ExceptionErrors::RESOURCE_ALREADY_EXISTS->value, $resourceName));
    }
}
