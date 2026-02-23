<?php

namespace App\Models;

use App\Enums\BloodGroup;
use App\Enums\Gender;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'user_id',
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
        'joining_date',
        'salary'
    ];

    protected $casts = [
        'gender' => Gender::class,
        'blood_group' => BloodGroup::class,
        'date_of_birth' => 'date',
        'joining_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function department()
    {
        return $this->belongsTo(AdminDepartment::class, 'admin_department_id');
    }
}
