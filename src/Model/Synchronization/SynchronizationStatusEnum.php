<?php

declare(strict_types=1);

namespace App\Model\Synchronization;

enum SynchronizationStatusEnum: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}
