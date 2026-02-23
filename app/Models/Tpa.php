<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tpa extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'contact_number',
        'address',
        'contact_person_name',
        'contact_person_phone',
        'status'
    ];

    /**
     * Relationship: One TPA can be linked to many patients
     */
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class, 'tpa_id');
    }
}
