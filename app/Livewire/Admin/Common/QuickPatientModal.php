<?php

namespace App\Livewire\Admin\Common;

use App\Models\{Patient, User};
use App\Enums\{UserRole, Gender, BloodGroup, MaritalStatus};
use Livewire\Component;
use Illuminate\Support\Facades\{Hash, DB};
use Carbon\Carbon;

class QuickPatientModal extends Component
{
    // Form Properties
    public $name, $phone, $email, $gender_val, $blood_group, $marital_status;
    public $date_of_birth, $guardian_name, $address;
    public $age_year, $age_month, $age_day;

    public function updatedDateOfBirth($value)
    {
        if ($value) {
            $diff = Carbon::parse($value)->diff(now());
            $this->age_year = $diff->y;
            $this->age_month = $diff->m;
        }
    }

    public function updatedAgeYear() { $this->calculateDob(); }
    public function updatedAgeMonth() { $this->calculateDob(); }

    private function calculateDob()
    {
        $this->date_of_birth = now()
            ->subYears((int)$this->age_year)
            ->subMonths((int)$this->age_month)
            ->format('Y-m-d');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:users,phone',
            'gender_val' => 'required',
            'date_of_birth' => 'required|date',
        ]);

        $patient = DB::transaction(function () {
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
                'date_of_birth' => $this->date_of_birth,
                'blood_group' => $this->blood_group,
                'marital_status' => $this->marital_status,
                'address' => $this->address,
            ]);
        });

        // Dispatch event to parent (Appointment, OPD, or IPD)
        $this->dispatch('patientCreated', 
            id: $patient->id, 
            name: $patient->user->name
        );

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