<?php

declare(strict_types=1);

namespace App\Model\User;

enum LoginTypeEnum: string
{
    case EMAIL = 'email';
    case GOOGLE = 'google';
}
