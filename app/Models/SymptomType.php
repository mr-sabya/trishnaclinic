<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SymptomType extends Model
{
    protected $fillable = [
        'name'
    ];

    public function titles()
    {
        return $this->hasMany(SymptomTitle::class);
    }
}
