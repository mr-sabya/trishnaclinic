<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathologyUnit extends Model
{
    protected $fillable = ['name'];

    public function parameters()
    {
        return $this->hasMany(PathologyParameter::class);
    }
}
