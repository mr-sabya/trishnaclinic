<?php

namespace App\Livewire\Admin\Common;

use App\Models\{Patient, User};
use App\Enums\{UserRole, Gender, BloodGroup, MaritalStatus};
use Livewire\Component;
use Illuminate\Support\Facades\{Hash, DB};

class QuickPatientModal extends Component
{
    public $name, $phone, $email, $gender_val, $blood_group, $marital_status;
    public $guardian_name, $address, $age_year, $age_month;

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:users,phone',
            'gender_val' => 'required',
            'age_year' => 'required|numeric|min:0',
        ]);

        // Format: "25Y 6M" or "25Y"
        $ageString = $this->age_year . 'Y' . ($this->age_month ? ' ' . $this->age_month . 'M' : '');

        $patient = DB::transaction(function () use ($ageString) {
            $user = User::create([
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'role' => UserRole::PATIENT,
                'password' => Hash::make($this->phone),
            ]);

            return Patient::create([
                'user_id' => $user->id,
                'mrn_number' => Patient::generateMrn(),
                'guardian_name' => $this->guardian_name,
                'gender' => $this->gender_val,
                'age' => $ageString,
                'blood_group' => $this->blood_group,
                'marital_status' => $this->marital_status,
                'address' => $this->address,
            ]);
        });

        $this->dispatch('patientCreated', id: $patient->id, name: $patient->user->name);
        $this->dispatch('closeModal');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.common.quick-patient-modal', [
            'genders' => Gender::cases(),
            'blood_groups' => BloodGroup::cases(),
            'marital_statuses' => MaritalStatus::cases(),
        ]);
    }
}
