<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RadiologyParameter extends Model
{
    protected $fillable = [
        'parameter_name',
        'reference_range_from',
        'reference_range_to',
        'radiology_unit_id',
        'description'
    ];

    public function unit()
    {
        return $this->belongsTo(RadiologyUnit::class, 'radiology_unit_id');
    }

    public function tests()
    {
        return $this->belongsToMany(RadiologyTest::class, 'radiology_test_parameter');
    }
}
