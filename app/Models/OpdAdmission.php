<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpdAdmission extends Model
{
    protected $fillable = [
        'opd_number',
        'patient_id',
        'doctor_id',
        'appointment_date',
        'case_type',
        'is_casualty',
        'is_old_patient',
        'refference',
        'symptoms_description',
        'known_allergies',
        'note',
        'status'
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'is_casualty' => 'boolean',
        'is_old_patient' => 'boolean',
    ];

    // --- Relationships ---

    /**
     * Relationship: One admission can have multiple symptoms recorded.
     */
    public function symptoms(): HasMany
    {
        return $this->hasMany(OpdAdmissionSymptom::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function charges(): HasMany
    {
        return $this->hasMany(OpdAdmissionCharge::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(OpdAdmissionPayment::class);
    }

    // --- Business Logic Helpers ---

    /**
     * Helper: Generate Unique OPD Number: OPDN-24-0001
     */
    public static function generateOpdNumber()
    {
        $latest = self::latest()->first();
        $nextNum = $latest ? ((int) substr($latest->opd_number, -4)) + 1 : 1;
        return 'OPDN-' . date('y') . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    // --- Accessors for Financials ---

    /**
     * Accessor: Sum of all added charges (Consultation, X-Ray, etc.)
     */
    public function getGrandTotalAttribute()
    {
        return $this->charges->sum('net_amount');
    }

    /**
     * Accessor: Sum of all recorded payments
     */
    public function getTotalPaidAttribute()
    {
        return $this->payments->sum('paid_amount');
    }

    /**
     * Accessor: Remaining balance
     */
    public function getBalanceAttribute()
    {
        return $this->grand_total - $this->total_paid;
    }
}
