<?php

namespace App\Enums;

enum MaritalStatus: string
{
    case SINGLE = 'Single';
    case MARRIED = 'Married';
    case WIDOWED = 'Widowed';
    case SEPARATED = 'Separated';
    case NOT_SPECIFIED = 'Not Specified';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
