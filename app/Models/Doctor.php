<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'gender',
        'photo',
        'address',
        'medical_department_id',
        'specialist_id',
        'designation',
        'qualification',
        'experience',
        'appointment_doctor_fee',
        'appointment_hospital_fee',
        'opd_doctor_fee',
        'opd_hospital_fee',
        'ipd_doctor_fee',
        'ipd_hospital_fee',
        'is_active',
        'type' // 'permanent' or 'on_call'
    ];

    // --- Relationships ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(MedicalDepartment::class, 'medical_department_id');
    }

    public function specialist(): BelongsTo
    {
        return $this->belongsTo(Specialist::class);
    }

    // --- Accessors ---

    // Allows you to call $doctor->name instead of $doctor->user->name
    public function getNameAttribute()
    {
        return $this->user->name;
    }

    // --- Scopes ---

    public function scopePermanent(Builder $query): Builder
    {
        return $query->where('type', 'permanent');
    }

    public function scopeOnCall(Builder $query): Builder
    {
        return $query->where('type', 'on_call');
    }
}
