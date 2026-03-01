<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;

class DoctorSchedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'global_shift_id',
        'available_days',
        'start_time',
        'end_time',
        'avg_consultation_time',
        'max_appointments',
        'status'
    ];

    protected $casts = [
        'available_days' => AsEnumCollection::class . ':' . DayOfWeek::class,
        'status' => 'boolean'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function shift()
    {
        return $this->belongsTo(GlobalShift::class, 'global_shift_id');
    }
}
