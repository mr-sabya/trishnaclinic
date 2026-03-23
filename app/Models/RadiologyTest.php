<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RadiologyTest extends Model
{
    protected $fillable = [
        'test_name',
        'short_name',
        'test_type',
        'radiology_category_id',
        'sub_category',
        'method',
        'report_days',
        'charge_id'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(RadiologyCategory::class, 'radiology_category_id');
    }

    public function charge(): BelongsTo
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(RadiologyParameter::class, 'radiology_test_parameter')
            ->withTimestamps();
    }

    // Financial Accessors
    public function getTotalAmountAttribute()
    {
        if (!$this->charge) return 0;
        $standard = (float) $this->charge->standard_charge;
        $taxPercent = (float) ($this->charge->tax->percentage ?? 0);
        return $standard + ($standard * $taxPercent / 100);
    }

    public function getTaxPercentageAttribute()
    {
        return $this->charge->tax->percentage ?? 0;
    }
}
