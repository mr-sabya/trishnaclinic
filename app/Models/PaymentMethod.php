<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'account_number',
        'account_holder',
        'bank_name',
        'bank_branch',
        'is_active',
        'is_default'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Relationship: A payment method can be used for many appointments
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Helper to get active methods for dropdowns
     */
    public static function getActiveMethods()
    {
        return self::where('is_active', true)->orderBy('is_default', 'desc')->get();
    }
}
