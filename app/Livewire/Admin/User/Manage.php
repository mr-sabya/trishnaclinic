<?php

namespace App\Livewire\Admin\User;

use App\Models\{User, Staff, AdminDepartment};
use App\Enums\{UserRole, Gender, BloodGroup};
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\{Hash, DB, Storage};
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class Manage extends Component
{
    use WithFileUploads;

    public $userId = null;

    // User Table Fields
    public $name, $email, $phone, $password, $role, $is_active = true;

    // Staff Table Fields
    public $admin_department_id, $employee_id, $nid_number, $father_name, $mother_name;
    public $gender, $blood_group, $present_address, $permanent_address, $designation;
    public $qualification, $joining_date, $salary, $remarks;

    // Media Fields
    public $photo, $existingPhoto;
    public $documents = []; // New uploads
    public $existingDocuments = []; // Already saved in DB

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
                $this->qualification = $user->staff->qualification;
                $this->present_address = $user->staff->present_address;
                $this->permanent_address = $user->staff->permanent_address;
                $this->salary = $user->staff->salary;
                $this->joining_date = $user->staff->joining_date?->format('Y-m-d');
                $this->remarks = $user->staff->remarks;

                // Media
                $this->existingPhoto = $user->staff->photo;
                $this->existingDocuments = $user->staff->documents ?? [];

                if ($user->staff->date_of_birth) {
                    $this->dob_day = $user->staff->date_of_birth->format('d');
                    $this->dob_month = $user->staff->date_of_birth->format('m');
                    $this->dob_year = $user->staff->date_of_birth->format('Y');
                }
            }
        }
    }

    public function removeExistingDocument($index)
    {
        unset($this->existingDocuments[$index]);
        $this->existingDocuments = array_values($this->existingDocuments);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', Rule::unique('users')->ignore($this->userId)],
            'role' => 'required',
            'admin_department_id' => 'required',
            'nid_number' => ['required', Rule::unique('staff')->ignore($this->userId, 'user_id')],
            'gender' => 'required',
            'dob_day' => 'required',
            'dob_month' => 'required',
            'dob_year' => 'required',
            'photo' => 'nullable|image|max:1024',
            'documents.*' => 'nullable|mimes:pdf,jpg,png,doc,docx|max:2048',
        ]);

        try {
            DB::transaction(function () {
                $dob = Carbon::createFromDate($this->dob_year, $this->dob_month, $this->dob_day)->format('Y-m-d');

                // 1. Process User
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
                    $userData['password'] = Hash::make($this->phone);
                }
                $user = User::updateOrCreate(['id' => $this->userId], $userData);

                // 2. Handle Photo
                $photoPath = $this->existingPhoto;
                if ($this->photo) {
                    $photoPath = $this->photo->store('staff_photos', 'public');
                }

                // 3. Handle Documents (Merge existing with new)
                $docPaths = $this->existingDocuments;
                if ($this->documents) {
                    foreach ($this->documents as $doc) {
                        $docPaths[] = $doc->store('staff_documents', 'public');
                    }
                }

                // 4. Process Staff
                Staff::updateOrCreate(['user_id' => $user->id], [
                    'admin_department_id' => $this->admin_department_id,
                    'photo' => $photoPath,
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
                    'qualification' => $this->qualification,
                    'joining_date' => $this->joining_date,
                    'salary' => $this->salary,
                    'documents' => $docPaths,
                    'remarks' => $this->remarks,
                    'is_active' => $this->is_active,
                ]);
            });

            session()->flash('success', 'Staff information saved successfully.');
            return redirect()->route('admin.users.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    // Helper to check if a file is an image for preview
    public function isImage($file)
    {
        if (is_string($file)) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            return in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        }

        if ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            return str_starts_with($file->getMimeType(), 'image/');
        }

        return false;
    }

    // Remove a document from the "newly uploaded" list before saving
    public function removeNewDocument($index)
    {
        unset($this->documents[$index]);
        $this->documents = array_values($this->documents);
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
            'years' => range(date('Y'), 1950),
        ]);
    }
}
