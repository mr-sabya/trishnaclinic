<?php

namespace App\Livewire\Admin\Patient;

use App\Models\{Patient, User, Tpa};
use App\Enums\{UserRole, Gender, BloodGroup, MaritalStatus};
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\{Hash, DB};
use Illuminate\Validation\Rule;

class Manage extends Component
{
    use WithFileUploads;

    public $patientId;

    // Form Properties
    public $name, $phone, $email, $guardian_name, $gender, $blood_group, $marital_status;
    public $date_of_birth, $identification_number, $address, $known_allergies, $remarks;
    public $tpa_id, $insurance_id, $tpa_validity, $photo, $existingPhoto, $mrn_display;

    // Searchable TPA Properties
    public $tpa_search = '';
    public $selected_tpa_name = 'No Referrer (Direct)';
    public $showTpaDropdown = false; // Added to control dropdown manually

    // Age Helper Properties (YY - MM)
    public $age_year, $age_month;

    public function mount($id = null)
    {
        if ($id) {
            $this->patientId = $id;
            $patient = Patient::with(['user', 'tpa'])->findOrFail($id);

            $this->name = $patient->user->name;
            $this->phone = $patient->user->phone;
            $this->email = $patient->user->email;
            $this->mrn_display = $patient->mrn_number;

            $this->guardian_name = $patient->guardian_name;
            $this->gender = $patient->gender instanceof Gender ? $patient->gender->value : $patient->gender;
            $this->blood_group = $patient->blood_group instanceof BloodGroup ? $patient->blood_group->value : $patient->blood_group;
            $this->marital_status = $patient->marital_status instanceof MaritalStatus ? $patient->marital_status->value : $patient->marital_status;

            $this->date_of_birth = $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : null;
            $this->identification_number = $patient->identification_number;
            $this->address = $patient->address;
            $this->known_allergies = $patient->known_allergies;
            $this->remarks = $patient->remarks;

            $this->tpa_id = $patient->tpa_id;
            $this->selected_tpa_name = $patient->tpa ? $patient->tpa->name : 'No Referrer (Direct)';

            $this->insurance_id = $patient->insurance_id;
            $this->tpa_validity = $patient->tpa_validity ? $patient->tpa_validity->format('Y-m-d') : null;
            $this->existingPhoto = $patient->photo;

            if ($patient->age) {
                preg_match('/(\d+)Y/', $patient->age, $years);
                preg_match('/(\d+)M/', $patient->age, $months);
                $this->age_year = $years[1] ?? null;
                $this->age_month = $months[1] ?? null;
            }
        }
    }

    public function selectTpa($id, $name)
    {
        $this->tpa_id = $id;
        $this->selected_tpa_name = $name ?: 'No Referrer (Direct)';
        $this->tpa_search = '';
        $this->showTpaDropdown = false; // Close dropdown
    }

    public function updatedTpaSearch()
    {
        if (!empty($this->tpa_search)) {
            $this->showTpaDropdown = true;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', Rule::unique('users')->ignore($this->patientId ? Patient::find($this->patientId)->user_id : null)],
            'gender' => 'required',
            'photo' => 'nullable|image|max:1024',
        ]);

        $ageString = trim(
            ($this->age_year ? $this->age_year . 'Y ' : '') .
                ($this->age_month ? $this->age_month . 'M' : '')
        );

        DB::transaction(function () use ($ageString) {
            $patient_record = $this->patientId ? Patient::find($this->patientId) : null;

            $user = User::updateOrCreate(
                ['id' => $patient_record ? $patient_record->user_id : null],
                [
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'role' => UserRole::PATIENT,
                    'password' => $patient_record ? User::find($patient_record->user_id)->password : Hash::make($this->phone),
                ]
            );

            $photoPath = $this->existingPhoto;
            if ($this->photo) {
                $photoPath = $this->photo->store('patients', 'public');
            }

            Patient::updateOrCreate(
                ['id' => $this->patientId],
                [
                    'user_id' => $user->id,
                    'mrn_number' => $patient_record ? $patient_record->mrn_number : Patient::generateMrn(),
                    'guardian_name' => $this->guardian_name,
                    'gender' => $this->gender,
                    'date_of_birth' => $this->date_of_birth ?: null,
                    'age' => $ageString,
                    'blood_group' => $this->blood_group,
                    'marital_status' => $this->marital_status,
                    'identification_number' => $this->identification_number,
                    'address' => $this->address,
                    'known_allergies' => $this->known_allergies,
                    'remarks' => $this->remarks,
                    'tpa_id' => $this->tpa_id ?: null,
                    'insurance_id' => $this->insurance_id,
                    'tpa_validity' => $this->tpa_validity ?: null,
                    'photo' => $photoPath,
                ]
            );
        });

        return redirect()->route('admin.patient.index')->with('success', 'Patient record updated successfully.');
    }

    public function render()
    {
        $tpas = Tpa::where('status', true)
            ->when($this->tpa_search, function ($query) {
                $query->where('name', 'like', '%' . $this->tpa_search . '%');
            })
            ->orderBy('name', 'asc')
            ->limit(10)
            ->get();

        return view('livewire.admin.patient.manage', [
            'tpas' => $tpas,
            'genders' => Gender::cases(),
            'blood_groups' => BloodGroup::cases(),
            'marital_statuses' => MaritalStatus::cases(),
        ]);
    }
}
