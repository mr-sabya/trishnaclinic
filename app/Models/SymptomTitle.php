<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SymptomTitle extends Model
{
    protected $fillable = [
        'symptom_type_id',
        'title',
        'description'
    ];


    public function type()
    {
        return $this->belongsTo(SymptomType::class, 'symptom_type_id');
    }
}
