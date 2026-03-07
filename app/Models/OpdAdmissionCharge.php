<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpdAdmissionCharge extends Model
{
    protected $fillable = [
        'opd_admission_id',
        'charge_id',
        'standard_charge',
        'tpa_charge',
        'applied_charge',
        'tax_percentage',
        'tax_amount',
        'discount_percentage',
        'discount_amount',
        'net_amount'
    ];

    public function admission()
    {
        return $this->belongsTo(OpdAdmission::class, 'opd_admission_id');
    }
    public function chargeMaster()
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }
}
