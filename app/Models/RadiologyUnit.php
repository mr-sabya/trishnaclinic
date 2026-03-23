<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RadiologyUnit extends Model
{
    protected $fillable = ['name'];

    public function parameters()
    {
        return $this->hasMany(RadiologyParameter::class);
    }
}
