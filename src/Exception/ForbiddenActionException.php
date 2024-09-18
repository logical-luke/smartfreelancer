<?php

declare(strict_types=1);

namespace App\Exception;

use App\Enum\ExceptionErrors;
use Symfony\Component\Uid\Uuid;

class ForbiddenActionException extends \RuntimeException
{
    public function __construct(string $action, string $resourceName, Uuid $id)
    {
        parent::__construct();

        $this->message = sprintf(ExceptionErrors::FORBIDDEN_ACTION->value, $action, $resourceName, $id->toRfc4122());
    }
}
