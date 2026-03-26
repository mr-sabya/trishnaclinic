<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\BloodGroup;
use App\Enums\MaritalStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'tpa_id',
        'mrn_number',
        'guardian_name',
        'date_of_birth',
        'age',
        'gender',
        'blood_group',
        'marital_status',
        'photo',
        'identification_number',
        'address',
        'known_allergies',
        'remarks',
        'insurance_id',
        'tpa_validity'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'tpa_validity' => 'date',
        'gender' => Gender::class,
        'blood_group' => BloodGroup::class,
        'marital_status' => MaritalStatus::class,
    ];

    /**
     * Relationship: Link to User Login Account
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Link to TPA (Insurance)
     */
    public function tpa(): BelongsTo
    {
        return $this->belongsTo(Tpa::class, 'tpa_id');
    }

    /**
     * Helper: Generate unique MRN (Medical Record Number)
     * Format: PAT-24-0001
     */
    public static function generateMrn(): string
    {
        $latest = self::latest()->first();
        $nextNumber = $latest ? ((int) substr($latest->mrn_number, -4)) + 1 : 1;
        return 'PAT-' . date('y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    
}
