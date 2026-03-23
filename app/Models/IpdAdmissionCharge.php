<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpdAdmissionCharge extends Model
{
    protected $fillable = [
        'ipd_admission_id',
        'charge_id',
        'standard_charge',
        'applied_charge',
        'tax_percentage',
        'tax_amount',
        'discount_percentage',
        'discount_amount',
        'net_amount'
    ];

    public function admission()
    {
        return $this->belongsTo(IpdAdmission::class);
    }
    public function chargeMaster()
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }
}
