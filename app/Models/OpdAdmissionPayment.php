<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpdAdmissionPayment extends Model
{
    protected $fillable = [
        'opd_admission_id',
        'payment_method_id',
        'paid_amount',
        'cheque_no',
        'cheque_date',
        'document'
    ];

    protected $casts = ['cheque_date' => 'date'];

    public function method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
