<?php

namespace App\Enums;

enum AppointmentPriority: int
{
    case NORMAL = 1;
    case URGENT = 2;
    case VERY_URGENT = 3;
    case LOW = 5;

    public function label(): string
    {
        return match ($this) {
            self::NORMAL => 'Normal',
            self::URGENT => 'Urgent',
            self::VERY_URGENT => 'Very Urgent',
            self::LOW => 'Low',
        };
    }
}
