<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargeType extends Model
{
    protected $fillable = [
        'name',
        'modules',
        'status'
    ];
    protected $casts = [
        'modules' => 'array',
        'status' => 'boolean'
    ];

    public function categories()
    {
        return $this->hasMany(ChargeCategory::class);
    }
}
