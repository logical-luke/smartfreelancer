<?php

declare(strict_types=1);

namespace App\Exception;

use App\Enum\ExceptionErrors;

class MissingPayloadFieldException extends InvalidPayloadException
{
    public function __construct(string $fieldName)
    {
        parent::__construct(sprintf(ExceptionErrors::MISSING_PAYLOAD_FIELD->value, $fieldName));
    }
}
