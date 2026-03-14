<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathologyParameter extends Model
{
    protected $fillable = [
        'parameter_name',
        'reference_range_from',
        'reference_range_to',
        'pathology_unit_id',
        'description'
    ];

    public function unit()
    {
        return $this->belongsTo(PathologyUnit::class, 'pathology_unit_id');
    }

    public function tests()
    {
        return $this->belongsToMany(PathologyTest::class, 'pathology_test_parameter');
    }
}
