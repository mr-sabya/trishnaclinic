<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TpaCharge extends Model
{
    protected $fillable = [
        'charge_id',
        'tpa_id',
        'scheduled_charge'
    ];

    public function tpa()
    {
        return $this->belongsTo(Tpa::class);
    }
}
