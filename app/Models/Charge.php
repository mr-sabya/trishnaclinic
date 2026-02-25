<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $fillable = [
        'charge_category_id',
        'tax_category_id',
        'unit_id',
        'name',
        'code',
        'standard_charge',
        'description'
    ];

    public function category()
    {
        return $this->belongsTo(ChargeCategory::class, 'charge_category_id');
    }
    public function tax()
    {
        return $this->belongsTo(TaxCategory::class, 'tax_category_id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Helper to calculate Total Price (Charge + Tax)
     */
    public function getTotalWithTaxAttribute()
    {
        $taxPercent = $this->tax->percentage ?? 0;
        return $this->standard_charge + ($this->standard_charge * $taxPercent / 100);
    }

    public function tpaPrices()
    {
        return $this->hasMany(TpaCharge::class);
    }
}
