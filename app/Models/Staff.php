<?php

namespace App\Models;

use App\Enums\BloodGroup;
use App\Enums\Gender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Staff extends Model
{
    protected $fillable = [
        'user_id',
        'photo',                // New
        'admin_department_id',
        'employee_id',
        'nid_number',
        'father_name',
        'mother_name',
        'gender',
        'blood_group',
        'date_of_birth',
        'present_address',
        'permanent_address',
        'designation',
        'qualification',        // New
        'joining_date',
        'salary',
        'documents',            // New (JSON/Array)
        'remarks',              // New
        'is_active'             // New
    ];

    protected $casts = [
        'gender' => Gender::class,
        'blood_group' => BloodGroup::class,
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'documents' => 'array',  // Automatically handles JSON encoding/decoding
        'is_active' => 'boolean'
    ];

    // --- Relationships ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(AdminDepartment::class, 'admin_department_id');
    }

    // --- Accessors ---

    // Allows you to call $staff->name directly
    public function getNameAttribute()
    {
        return $this->user->name;
    }
}
