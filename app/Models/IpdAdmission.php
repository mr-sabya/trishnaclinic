<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IpdAdmission extends Model
{
    protected $fillable = [
        'ipd_number',
        'patient_id',
        'doctor_id',
        'bed_id',
        'admission_date',
        'case_type',
        'is_casualty',
        'refference',
        'symptoms_description',
        'known_allergies',
        'note',
        'status',
        'doctor_fee',
        'hospital_fee',
        'discount_percentage',
        'discount_amount',
        'net_amount',
        'discharge_date'
    ];

    protected $casts = [
        'admission_date' => 'datetime',
        'discharge_date' => 'datetime',
        'is_casualty' => 'boolean',
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
    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }
    public function symptoms(): HasMany
    {
        return $this->hasMany(IpdAdmissionSymptom::class);
    }
    public function charges(): HasMany
    {
        return $this->hasMany(IpdAdmissionCharge::class);
    }
    public function payments(): HasMany
    {
        return $this->hasMany(IpdAdmissionPayment::class);
    }

    // --- Helpers ---

    public static function generateIpdNumber()
    {
        $latest = self::latest()->first();
        $nextNum = $latest ? ((int) substr($latest->ipd_number, -4)) + 1 : 1;
        return 'IPDN-' . date('y') . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    public function getGrandTotalAttribute()
    {
        return ($this->attributes['net_amount'] ?? 0) + $this->charges->sum('net_amount');
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments->sum('paid_amount');
    }
    public function getBalanceAttribute()
    {
        return $this->grand_total - $this->total_paid;
    }
}
