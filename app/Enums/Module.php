<?php

namespace App\Enums;

enum Module: string
{
    case OPD = 'opd'; // Out-Patient
    case IPD = 'ipd'; // In-Patient
    case PATHOLOGY = 'pathology';
    case RADIOLOGY = 'radiology';
    case BLOOD_BANK = 'blood_bank';
    case AMBULANCE = 'ambulance';
    case PHARMACY = 'pharmacy';
    case APPOINTMENT = 'appointment';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
