<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpdAdmissionSymptom extends Model
{
    protected $fillable = ['ipd_admission_id', 'symptom_type_id', 'symptom_title_id'];

    public function type()
    {
        return $this->belongsTo(SymptomType::class);
    }
    public function title()
    {
        return $this->belongsTo(SymptomTitle::class);
    }
}
