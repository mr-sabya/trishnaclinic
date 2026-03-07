<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpdAdmissionSymptom extends Model
{
    protected $fillable = [
        'opd_admission_id',
        'symptom_type_id',
        'symptom_title_id'
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(SymptomType::class, 'symptom_type_id');
    }

    public function title(): BelongsTo
    {
        return $this->belongsTo(SymptomTitle::class, 'symptom_title_id');
    }
}
