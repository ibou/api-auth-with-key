<?php

declare(strict_types=1);

namespace App\Enum;

enum DailyQuestStatusEnum: string
{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
}
