<?php

declare(strict_types=1);

namespace App\Enum;

enum ExceptionErrors: string
{
    case INVALID_PAYLOAD = 'Invalid payload';
    case RESOURCE_NOT_FOUND = 'Resource not found: %s; id: %s';
    case FORBIDDEN_ACTION = 'Forbidden action: %s; resource: %s; id: %s';
    case RESOURCE_ALREADY_EXISTS = 'Resource already exists: %s';
    case MISSING_PAYLOAD_FIELD = 'Missing payload field: %s';
    case UNABLE_TO_PARSE_JSON = 'Unable to parse JSON';
}
