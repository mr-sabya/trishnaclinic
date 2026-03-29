<?php

namespace App\Livewire\Admin\Common;

use App\Models\{Patient, User, Tpa};
use App\Enums\{UserRole, Gender, BloodGroup, MaritalStatus};
use Livewire\Component;
use Illuminate\Support\Facades\{Hash, DB};

class QuickPatientModal extends Component
{
    public $name, $phone, $email, $gender_val, $blood_group, $marital_status;
    public $guardian_name, $address, $age_year, $age_month;

    // TPA Properties
    public $tpa_id, $tpa_validity, $tpa_search = '', $selected_tpa_name = 'Direct/Cash';

    public function selectTpa($id, $name)
    {
        $this->tpa_id = $id;
        $this->selected_tpa_name = $name;
        $this->tpa_search = '';

        // Reset validity if TPA is cleared
        if (!$id) {
            $this->tpa_validity = null;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:users,phone',
            'gender_val' => 'required',
            'age_year' => 'required|numeric|min:0',
        ]);

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
                'tpa_id' => $this->tpa_id ?: null,
                'tpa_validity' => $this->tpa_validity ?: null, // Added validity
            ]);
        });

        $this->dispatch('patientCreated', id: $patient->id, name: $patient->user->name);
        $this->dispatch('closeModal');
        $this->reset();
    }

    public function render()
    {
        $tpas = Tpa::where('status', true)
            ->when($this->tpa_search, function ($query) {
                $query->where('name', 'like', '%' . $this->tpa_search . '%');
            })
            ->orderBy('name', 'asc')
            ->limit(5)
            ->get();

        return view('livewire.admin.common.quick-patient-modal', [
            'genders' => Gender::cases(),
            'blood_groups' => BloodGroup::cases(),
            'marital_statuses' => MaritalStatus::cases(),
            'tpas' => $tpas,
        ]);
    }
}
