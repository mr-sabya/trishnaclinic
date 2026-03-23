<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpdAdmissionPayment extends Model
{
    protected $fillable = [
        'ipd_admission_id',
        'payment_method_id',
        'paid_amount',
        'cheque_no',
        'cheque_date',
        'document',
        'note'
    ];

    public function admission()
    {
        return $this->belongsTo(IpdAdmission::class);
    }
    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
