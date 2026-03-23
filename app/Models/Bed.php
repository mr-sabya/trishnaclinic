<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bed extends Model
{
    protected $fillable = [
        'name',
        'bed_type_id',
        'bed_group_id',
        'is_active' // boolean: true = functional, false = broken/blocked
    ];

    public function bedType(): BelongsTo
    {
        return $this->belongsTo(BedType::class);
    }

    public function bedGroup(): BelongsTo
    {
        return $this->belongsTo(BedGroup::class);
    }

    /**
     * Helper: Check if the bed is currently occupied in IPD
     */
    public function isOccupied(): bool
    {
        // Check if there is an active IPD admission (status = 'admitted') for this bed
        return IpdAdmission::where('bed_id', $this->id)
            ->where('status', 'admitted')
            ->exists();
    }
}
