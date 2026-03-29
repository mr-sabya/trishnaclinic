<?php

namespace App\Livewire\Admin\Doctor;

use App\Models\{Doctor, User, MedicalDepartment, Specialist};
use App\Enums\UserRole;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\{DB, Hash};

class Manage extends Component
{
    use WithFileUploads;

    public $doctorId;

    // User Fields (from users table)
    public $name, $phone, $email;

    // Doctor Fields (from doctors table)
    public $gender, $photo, $existingPhoto, $address, $type = 'permanent';
    public $medical_department_id, $specialist_id, $designation, $qualification, $experience;

    // Fee Splits
    public $appointment_doctor_fee = 0, $appointment_hospital_fee = 0;
    public $opd_doctor_fee = 0, $opd_hospital_fee = 0;
    public $ipd_doctor_fee = 0, $ipd_hospital_fee = 0;

    public $is_active = true;

    public function mount($doctorId = null)
    {
        if ($doctorId) {
            $this->doctorId = $doctorId;
            $doctor = Doctor::with('user')->findOrFail($doctorId);

            // Map User Data
            $this->name = $doctor->user->name;
            $this->phone = $doctor->user->phone;
            $this->email = $doctor->user->email;

            // Map Doctor Data
            $this->fill($doctor->toArray());
            $this->existingPhoto = $doctor->photo;
        }
    }

    public function save()
    {
        // Get the related user ID if editing
        $relatedUserId = $this->doctorId ? Doctor::find($this->doctorId)->user_id : null;

        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', Rule::unique('users')->ignore($relatedUserId)],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($relatedUserId)],
            'medical_department_id' => 'required',
            'specialist_id' => 'required',
            'designation' => 'required',
            'qualification' => 'required',
            'type' => 'required|in:permanent,on_call',
            'photo' => 'nullable|image|max:1024',
        ]);

        DB::transaction(function () use ($relatedUserId) {
            // 1. Update or Create User
            $user = User::updateOrCreate(
                ['id' => $relatedUserId],
                [
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'role' => UserRole::DOCTOR, // Assuming you have this Enum
                    'password' => $relatedUserId ? User::find($relatedUserId)->password : Hash::make($this->phone),
                ]
            );

            // 2. Handle Photo
            $photoPath = $this->existingPhoto;
            if ($this->photo) {
                $photoPath = $this->photo->store('doctors', 'public');
            }

            // 3. Update or Create Doctor
            Doctor::updateOrCreate(
                ['id' => $this->doctorId],
                [
                    'user_id' => $user->id,
                    'gender' => $this->gender,
                    'address' => $this->address,
                    'medical_department_id' => $this->medical_department_id,
                    'specialist_id' => $this->specialist_id,
                    'designation' => $this->designation,
                    'qualification' => $this->qualification,
                    'experience' => $this->experience,
                    'appointment_doctor_fee' => $this->appointment_doctor_fee,
                    'appointment_hospital_fee' => $this->appointment_hospital_fee,
                    'opd_doctor_fee' => $this->opd_doctor_fee,
                    'opd_hospital_fee' => $this->opd_hospital_fee,
                    'ipd_doctor_fee' => $this->ipd_doctor_fee,
                    'ipd_hospital_fee' => $this->ipd_hospital_fee,
                    'is_active' => $this->is_active,
                    'type' => $this->type,
                    'photo' => $photoPath,
                ]
            );
        });

        session()->flash('success', $this->doctorId ? 'Doctor profile updated.' : 'Doctor registered successfully.');
        return $this->redirect(route('admin.doctor.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.doctor.manage', [
            'departments' => MedicalDepartment::where('status', true)->get(),
            'specialists' => Specialist::all(),
        ]);
    }
}
