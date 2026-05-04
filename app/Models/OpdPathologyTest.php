<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpdPathologyTest extends Model
{
    protected $fillable = [
        'opd_admission_id',
        'pathology_test_id',
        'test_date',
        'status',
        'instruction'
    ];

    // ADD THIS CAST
    protected $casts = [
        'test_date' => 'datetime',
    ];

    public function admission()
    {
        return $this->belongsTo(OpdAdmission::class);
    }

    public function test()
    {
        return $this->belongsTo(PathologyTest::class, 'pathology_test_id');
    }
}
