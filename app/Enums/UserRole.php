<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case ACCOUNTANT = 'accountant';
    case RECEPTIONIST = 'receptionist';
    case NURSE = 'nurse';
    case FLOORMAN = 'floorman';
    case DIAGNOSTIC = 'diagnostic';
    case DISPENSARY = 'dispensary';
    case DOCTOR = 'doctor';
    case PATIENT = 'patient';
    // nurse role added for future use, currently not assigned to any user

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
