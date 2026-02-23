<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminDepartment extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'status'
    ];
}
