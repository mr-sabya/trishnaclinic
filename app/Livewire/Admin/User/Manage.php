<?php

namespace App\Livewire\Admin\User;

use App\Models\{User, Staff, AdminDepartment};
use App\Enums\{UserRole, Gender, BloodGroup};
use Livewire\Component;
use Illuminate\Support\Facades\{Hash, DB};
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class Manage extends Component
{
    public $userId = null;

    // User Table Fields
    public $name, $email, $phone, $password, $role, $is_active = true;

    // Staff Table Fields
    public $admin_department_id, $employee_id, $nid_number, $father_name, $mother_name;
    public $gender, $blood_group, $present_address, $permanent_address, $designation, $joining_date, $salary;

    // DOB Helper Fields
    public $dob_day, $dob_month, $dob_year;

    public function mount($userId = null)
    {
        if ($userId) {
            $this->userId = $userId;
            $user = User::with('staff')->findOrFail($userId);

            // Fill User Data
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->role = $user->role->value;
            $this->is_active = $user->is_active;

            // Fill Staff Data
            if ($user->staff) {
                $this->admin_department_id = $user->staff->admin_department_id;
                $this->employee_id = $user->staff->employee_id;
                $this->nid_number = $user->staff->nid_number;
                $this->father_name = $user->staff->father_name;
                $this->mother_name = $user->staff->mother_name;
                $this->gender = $user->staff->gender->value;
                $this->blood_group = $user->staff->blood_group?->value;
                $this->designation = $user->staff->designation;
                $this->present_address = $user->staff->present_address;
                $this->permanent_address = $user->staff->permanent_address;
                $this->salary = $user->staff->salary;
                $this->joining_date = $user->staff->joining_date?->format('Y-m-d');

                if ($user->staff->date_of_birth) {
                    $this->dob_day = $user->staff->date_of_birth->format('d');
                    $this->dob_month = $user->staff->date_of_birth->format('m');
                    $this->dob_year = $user->staff->date_of_birth->format('Y');
                }
            }
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', Rule::unique('users')->ignore($this->userId)],
            'role' => 'required',
            'admin_department_id' => 'required',
            'nid_number' => ['required', Rule::unique('staff')->ignore($this->userId, 'user_id')],
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'gender' => 'required',
            'dob_day' => 'required',
            'dob_month' => 'required',
            'dob_year' => 'required',
            'present_address' => 'required',
            'permanent_address' => 'required',
            'designation' => 'required',
            'joining_date' => 'required|date',
        ]);

        try {
            DB::transaction(function () {
                // 1. Combine DOB
                $dob = Carbon::createFromDate($this->dob_year, $this->dob_month, $this->dob_day)->format('Y-m-d');

                // 2. Process User
                $userData = [
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'role' => $this->role,
                    'is_active' => $this->is_active,
                ];

                if ($this->password) {
                    $userData['password'] = Hash::make($this->password);
                } elseif (!$this->userId) {
                    $userData['password'] = Hash::make($this->phone); // Default password
                }

                $user = User::updateOrCreate(['id' => $this->userId], $userData);

                // 3. Process Staff
                Staff::updateOrCreate(['user_id' => $user->id], [
                    'admin_department_id' => $this->admin_department_id,
                    'employee_id' => $this->employee_id ?? Staff::generateEmployeeId(),
                    'nid_number' => $this->nid_number,
                    'father_name' => $this->father_name,
                    'mother_name' => $this->mother_name,
                    'gender' => $this->gender,
                    'blood_group' => $this->blood_group,
                    'date_of_birth' => $dob,
                    'present_address' => $this->present_address,
                    'permanent_address' => $this->permanent_address,
                    'designation' => $this->designation,
                    'joining_date' => $this->joining_date,
                    'salary' => $this->salary,
                ]);
            });

            session()->flash('success', 'Staff information saved successfully.');
            return redirect()->route('admin.users.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.user.manage', [
            'departments' => AdminDepartment::where('status', true)->get(),
            'roles' => UserRole::cases(),
            'blood_groups' => BloodGroup::cases(),
            'genders' => Gender::cases(),
            'days' => range(1, 31),
            'months' => range(1, 12),
            'years' => range(date('Y') - 18, 1950),
        ]);
    }
}
