<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RadiologyCategory extends Model
{
    protected $fillable = ['name'];

    public function tests()
    {
        return $this->hasMany(RadiologyTest::class);
    }
}
