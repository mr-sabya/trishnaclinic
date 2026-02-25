<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargeCategory extends Model
{
    protected $fillable = [
        'charge_type_id',
        'name',
        'description'
    ];

    public function chargeType()
    {
        return $this->belongsTo(ChargeType::class);
    }

    public function charges()
    {
        return $this->hasMany(Charge::class);
    }
}
