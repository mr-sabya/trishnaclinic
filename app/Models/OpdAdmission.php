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
        'status',
        // New Financial Columns
        'doctor_fee',
        'hospital_fee',
        'discount_percentage',
        'discount_amount',
        'net_amount'
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


    /**
     * Relationship: Pathology tests ordered during this admission
     */
    public function pathologyTests(): HasMany
    {
        return $this->hasMany(OpdPathologyTest::class);
    }

    /**
     * Relationship: Radiology tests ordered during this admission
     */
    public function radiologyTests(): HasMany
    {
        return $this->hasMany(OpdRadiologyTest::class);
    }

    // --- Business Logic Helpers ---

    /**
     * Helper: Generate Unique OPD Number: OPDN-24-0001
     */
    public static function generateOpdNumber()
    {
        $latest = self::latest()->first();

        // We use -6 in substr to extract the last 6 digits accurately
        $nextNum = $latest ? ((int) substr($latest->opd_number, -6)) + 1 : 1;

        // This generates: 24-000001 (Year - 6 digits)
        return date('y') . '-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);
    }

    // --- Accessors for Financials ---

    /**
     * Accessor: Sum of Initial Admission Amount + All Additional Charges
     */
    public function getGrandTotalAttribute()
    {
        // Sum the net_amount from the admission itself + sum of all additional charges
        $initialAmount = $this->attributes['net_amount'] ?? 0;
        $additionalCharges = $this->charges->sum('net_amount');

        return $initialAmount + $additionalCharges;
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
