<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalShift extends Model
{
    protected $fillable = ['name', 'start_time', 'end_time'];
    public function slots()
    {
        return $this->hasMany(DoctorSchedule::class);
    }
}
