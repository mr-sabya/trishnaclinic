<?php

namespace App\Livewire\Admin\Patient;

use App\Models\{Patient, User, Tpa};
use App\Enums\{UserRole, Gender, BloodGroup, MaritalStatus};
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\{Hash, DB, Storage};
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class Manage extends Component
{
    use WithFileUploads;

    public $patientId;

    // Form Properties
    public $name, $phone, $email, $guardian_name, $gender, $blood_group, $marital_status;
    public $date_of_birth, $identification_number, $address, $known_allergies, $remarks;
    public $tpa_id, $insurance_id, $tpa_validity, $photo, $existingPhoto;

    // Age Helper Properties (yy-mm-dd)
    public $age_year, $age_month, $age_day;

    public function mount($id = null)
    {
        if ($id) {
            $this->patientId = $id;
            $patient = Patient::with('user')->findOrFail($id);

            $this->name = $patient->user->name;
            $this->phone = $patient->user->phone;
            $this->email = $patient->user->email;

            $this->fill($patient->toArray());
            $this->existingPhoto = $patient->photo;

            // Cast Enums for select values
            $this->gender = $patient->gender->value;
            $this->blood_group = $patient->blood_group?->value;
            $this->marital_status = $patient->marital_status?->value;

            // Format dates for input
            $this->date_of_birth = $patient->date_of_birth?->format('Y-m-d');
            $this->tpa_validity = $patient->tpa_validity?->format('Y-m-d');

            $this->calculateAgeFromDob();
        }
    }

    // Logic: If DOB is changed, update Age YY-MM-DD
    public function updatedDateOfBirth($value)
    {
        if ($value) {
            $this->calculateAgeFromDob();
        }
    }

    // Logic: If Age Year/Month/Day is changed, calculate DOB
    public function updatedAgeYear()
    {
        $this->calculateDobFromAge();
    }
    public function updatedAgeMonth()
    {
        $this->calculateDobFromAge();
    }
    public function updatedAgeDay()
    {
        $this->calculateDobFromAge();
    }

    private function calculateAgeFromDob()
    {
        if ($this->date_of_birth) {
            $diff = Carbon::parse($this->date_of_birth)->diff(now());
            $this->age_year = $diff->y;
            $this->age_month = $diff->m;
            $this->age_day = $diff->d;
        }
    }

    private function calculateDobFromAge()
    {
        $this->date_of_birth = now()
            ->subYears((int)$this->age_year)
            ->subMonths((int)$this->age_month)
            ->subDays((int)$this->age_day)
            ->format('Y-m-d');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', Rule::unique('users')->ignore($this->patientId ? Patient::find($this->patientId)->user_id : null)],
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'photo' => 'nullable|image|max:1024', // 1MB Max
        ]);

        DB::transaction(function () {
            // 1. Handle User Auth Record
            $user = User::updateOrCreate(
                ['id' => $this->patientId ? Patient::find($this->patientId)->user_id : null],
                [
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'role' => UserRole::PATIENT,
                    'password' => $this->patientId ? User::find(Patient::find($this->patientId)->user_id)->password : Hash::make($this->phone),
                ]
            );

            // 2. Handle Photo Upload
            $photoPath = $this->existingPhoto;
            if ($this->photo) {
                $photoPath = $this->photo->store('patients', 'public');
            }

            // 3. Handle Patient Record
            Patient::updateOrCreate(
                ['id' => $this->patientId],
                [
                    'user_id' => $user->id,
                    'mrn_number' => $this->patientId ? Patient::find($this->patientId)->mrn_number : Patient::generateMrn(),
                    'guardian_name' => $this->guardian_name,
                    'gender' => $this->gender,
                    'date_of_birth' => $this->date_of_birth,
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

        return redirect()->route('admin.patients.index')->with('success', 'Patient record saved.');
    }

    public function render()
    {
        return view('livewire.admin.patient.manage', [
            'tpas' => Tpa::where('status', true)->get(),
            'genders' => Gender::cases(),
            'blood_groups' => BloodGroup::cases(),
            'marital_statuses' => MaritalStatus::cases(),
        ]);
    }
}
