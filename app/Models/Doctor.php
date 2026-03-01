<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Doctor extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
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
        'is_active'
    ];

    // --- Relationships ---

    public function department(): BelongsTo
    {
        return $this->belongsTo(MedicalDepartment::class, 'medical_department_id');
    }

    public function specialist(): BelongsTo
    {
        return $this->belongsTo(Specialist::class);
    }

    // --- Accessors for Totals (Used in Billing/UI) ---

    public function getTotalAppointmentFeeAttribute()
    {
        return $this->appointment_doctor_fee + $this->appointment_hospital_fee;
    }

    public function getTotalOpdFeeAttribute()
    {
        return $this->opd_doctor_fee + $this->opd_hospital_fee;
    }

    public function getTotalIpdFeeAttribute()
    {
        return $this->ipd_doctor_fee + $this->ipd_hospital_fee;
    }
}
