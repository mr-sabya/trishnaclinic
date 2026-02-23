<?php

namespace App\Enums;

enum Gender: string
{
    case MALE = 'Male';
    case FEMALE = 'Female';
    case OTHER = 'Other';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
