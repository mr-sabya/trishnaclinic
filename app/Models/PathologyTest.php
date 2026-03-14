<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PathologyTest extends Model
{
    protected $fillable = [
        'test_name',
        'short_name',
        'test_type',
        'pathology_category_id',
        'sub_category',
        'method',
        'report_days',
        'charge_id' // This maps to the "code" field in your HTML select
    ];

    /**
     * Relationship: Category (Complete Blood Count, Lipid Profile, etc.)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PathologyCategory::class, 'pathology_category_id');
    }

    /**
     * Relationship: Charge Master Link
     * This pulls the Standard Charge and Tax from your financial setup.
     */
    public function charge(): BelongsTo
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }

    /**
     * Relationship: Parameters (The dynamic table in your HTML)
     * CBC contains Hemoglobin, WBC, RBC, etc.
     */
    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(PathologyParameter::class, 'pathology_test_parameter')
                    ->withTimestamps();
    }

    /**
     * Accessor: Calculate Total Amount (Charge + Tax)
     * Matches the "Amount (৳)" field in your HTML.
     */
    public function getTotalAmountAttribute()
    {
        if (!$this->charge) return 0;
        
        $standard = (float) $this->charge->standard_charge;
        $taxPercent = (float) ($this->charge->tax->percentage ?? 0);
        
        return $standard + ($standard * $taxPercent / 100);
    }

    /**
     * Accessor: Get Tax percentage only
     * Matches the "Tax (%)" field in your HTML.
     */
    public function getTaxPercentageAttribute()
    {
        return $this->charge->tax->percentage ?? 0;
    }
}