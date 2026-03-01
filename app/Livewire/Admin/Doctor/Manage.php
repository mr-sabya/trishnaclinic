<?php

namespace App\Livewire\Admin\Doctor;

use App\Models\Doctor;
use App\Models\MedicalDepartment;
use App\Models\Specialist;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;

class Manage extends Component
{
    use WithFileUploads;

    public $doctorId; // For edit mode

    // Form fields
    public $name, $phone, $email, $gender, $photo, $existingPhoto, $address;
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
            $doctor = Doctor::findOrFail($doctorId);

            $this->fill($doctor->toArray());
            $this->existingPhoto = $doctor->photo;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', Rule::unique('doctors')->ignore($this->doctorId)],
            'email' => ['nullable', 'email', Rule::unique('doctors')->ignore($this->doctorId)],
            'medical_department_id' => 'required',
            'specialist_id' => 'required',
            'designation' => 'required',
            'qualification' => 'required',
            'photo' => 'nullable|image|max:1024', // 1MB
        ]);

        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
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
        ];

        if ($this->photo) {
            $data['photo'] = $this->photo->store('doctors', 'public');
        }

        Doctor::updateOrCreate(['id' => $this->doctorId], $data);

        session()->flash('success', $this->doctorId ? 'Doctor updated.' : 'Doctor created.');
        return redirect()->route('admin.doctors.index');
    }

    public function render()
    {
        return view('livewire.admin.doctor.manage', [
            'departments' => MedicalDepartment::where('status', true)->get(),
            'specialists' => Specialist::all(),
        ]);
    }
}
