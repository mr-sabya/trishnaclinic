<?php

namespace App\Models;

use App\Enums\AppointmentPriority;
use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'appointment_number',
        'patient_id',
        'doctor_id',
        'charge_id',
        'date',
        'global_shift_id',
        'doctor_schedule_id',
        'time_slot',
        'priority',
        'status',
        'doctor_fees',
        'hospital_fees',
        'discount_percentage',
        'net_amount',
        'payment_method_id',
        'payment_status',
        'cheque_no',
        'cheque_date',
        'attachment',
        'message',
        'live_consult'
    ];

    protected $casts = [
        'date' => 'date',
        'cheque_date' => 'date',
        'priority' => AppointmentPriority::class, // Enum cast (Int)
        'status' => AppointmentStatus::class,     // Enum cast (String)
        'live_consult' => 'boolean',
    ];

    // --- Relationships ---

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function charge(): BelongsTo
    {
        return $this->belongsTo(Charge::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(GlobalShift::class, 'global_shift_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(DoctorSchedule::class, 'doctor_schedule_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    // --- Business Logic Helpers ---

    /**
     * Generate unique Appointment Number: APP-24-0001
     */
    public static function generateNumber(): string
    {
        $latest = self::latest()->first();
        $nextNum = $latest ? ((int) substr($latest->appointment_number, -4)) + 1 : 1;
        return 'APP-' . date('y') . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Attribute: Get the total fee before discount
     */
    public function getTotalGrossAttribute(): float
    {
        return $this->doctor_fees + $this->hospital_fees;
    }

    /**
     * Scope: Filter by Date (Used in Dashboard queues)
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', now()->toDateString());
    }
}
