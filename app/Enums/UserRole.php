<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case ACCOUNTANT = 'accountant';
    case RECEPTIONIST = 'receptionist';
    case MANAGER = 'manager';
    case FLOORMAN = 'floorman';
    case DIAGNOSTIC = 'diagnostic';
    case DISPENSARY = 'dispensary';
    case DOCTOR = 'doctor';
    case PATIENT = 'patient';

    // Helper to get labels for dropdowns
    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::DIAGNOSTIC => 'Diagnostic / Lab',
            self::DISPENSARY => 'Dispensary / Pharmacy',
            default => ucfirst($this->value),
        };
    }
}
