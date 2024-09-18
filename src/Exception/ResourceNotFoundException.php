<?php

declare(strict_types=1);

namespace App\Exception;

use App\Enum\ExceptionErrors;
use Symfony\Component\Uid\Uuid;

class ResourceNotFoundException extends \RuntimeException
{
    public function __construct(string $resourceName, Uuid $id)
    {
        parent::__construct();

        $this->message = sprintf(ExceptionErrors::RESOURCE_NOT_FOUND->value, $resourceName, $id->toRfc4122());
    }
}
