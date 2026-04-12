<?php

namespace App\Livewire\Admin\Appointment;

use App\Models\{Appointment, Patient, User, Doctor, GlobalShift, DoctorSchedule, PaymentMethod};
use App\Enums\{AppointmentPriority, AppointmentStatus, Gender, BloodGroup, MaritalStatus, UserRole};
use Livewire\{Component, WithFileUploads};
use Illuminate\Support\Facades\{Hash, DB};
use Carbon\Carbon;

class Manage extends Component
{
    use WithFileUploads;

    public $appointmentId;

    // --- Appointment Form Properties ---
    public $patient_id, $doctor_id, $date, $global_shift_id, $doctor_schedule_id, $time_slot;
    public $priority = 1, $status = 'pending', $payment_method_id;
    public $doctor_fees = 0, $hospital_fees = 0, $discount_percentage = 0, $net_amount = 0;
    public $message, $live_consult = false, $cheque_no, $cheque_date;

    // --- Patient Search & Schedule Properties ---
    public $patient_search = '', $patient_results = [];
    public $doctor_schedules = [];
    public $showPatientModal = false;

    // --- Quick Add Patient Properties ---
    public $name, $phone, $email, $guardian_name, $gender_val, $blood_group, $marital_status;
    public $date_of_birth, $identification_number, $address, $age_year, $age_month, $age_day;

    public function mount($id = null)
    {
        $this->date = date('Y-m-d');
        if ($id) {
            $this->appointmentId = $id;
            $app = Appointment::findOrFail($id);
            $this->fill($app->toArray());
            $this->date = $app->date->format('Y-m-d');
            $this->patient_search = $app->patient->user->name;
            $this->status = $app->status->value;
            $this->priority = $app->priority->value;
            $this->fetchSchedules();
        }
    }

    public function updatedPatientSearch($query)
    {
        if (strlen($query) < 2) { $this->patient_results = []; return; }
        $this->patient_results = Patient::with('user')
            ->whereHas('user', fn($u) => $u->where('name', 'like', "%$query%")->orWhere('phone', 'like', "%$query%"))
            ->orWhere('mrn_number', 'like', "%$query%")
            ->limit(5)->get();
    }

    public function selectPatient($id, $name)
    {
        $this->patient_id = $id;
        $this->patient_search = $name;
        $this->patient_results = [];
    }

    // --- Fee & Schedule Logic ---
    public function updatedDoctorId($value)
    {
        if ($value) {
            $doctor = Doctor::find($value);
            $this->doctor_fees = $doctor->appointment_doctor_fee ?? 0;
            $this->hospital_fees = $doctor->appointment_hospital_fee ?? 0;
            $this->calculateNet();
            $this->fetchSchedules();
        } else {
            $this->doctor_schedules = [];
        }
    }

    public function updatedGlobalShiftId()
    {
        $this->fetchSchedules();
    }

    private function fetchSchedules()
    {
        if (!$this->doctor_id) return;

        $query = DoctorSchedule::where('doctor_id', $this->doctor_id)->where('status', true);
        
        if ($this->global_shift_id) {
            $query->where('global_shift_id', $this->global_shift_id);
        }

        $this->doctor_schedules = $query->with('shift')->get();

        // Auto-select if only one schedule exists
        if (count($this->doctor_schedules) === 1) {
            $this->doctor_schedule_id = $this->doctor_schedules[0]->id;
        }
    }

    public function updatedDiscountPercentage() { $this->calculateNet(); }

    public function calculateNet() {
        $total = (float)$this->hospital_fees + (float)$this->doctor_fees;
        $this->net_amount = $total - ($total * (float)$this->discount_percentage / 100);
    }

    // --- Age/DOB Logic ---
    public function updatedDateOfBirth($value) { if ($value) $this->calculateAgeFromDob(); }
    public function updatedAgeYear() { $this->calculateDobFromAge(); }

    private function calculateAgeFromDob() {
        if ($this->date_of_birth) {
            $diff = Carbon::parse($this->date_of_birth)->diff(now());
            $this->age_year = $diff->y; $this->age_month = $diff->m; $this->age_day = $diff->d;
        }
    }

    private function calculateDobFromAge() {
        $this->date_of_birth = now()->subYears((int)$this->age_year)->subMonths((int)$this->age_month)->subDays((int)$this->age_day)->format('Y-m-d');
    }

    public function saveQuickPatient()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:users,phone',
            'gender_val' => 'required',
            'date_of_birth' => 'required|date',
        ]);

        DB::transaction(function () {
            $user = User::create([
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'role' => UserRole::PATIENT,
                'password' => Hash::make($this->phone),
            ]);

            $patient = Patient::create([
                'user_id' => $user->id,
                'mrn_number' => Patient::generateMrn(),
                'guardian_name' => $this->guardian_name,
                'gender' => $this->gender_val,
                'date_of_birth' => $this->date_of_birth,
                'blood_group' => $this->blood_group,
                'marital_status' => $this->marital_status,
                'address' => $this->address,
            ]);
            $this->selectPatient($patient->id, $user->name);
        });
        $this->showPatientModal = false;
    }

    public function save()
    {
        $this->validate([
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'doctor_schedule_id' => 'required',
            'date' => 'required|date',
            'global_shift_id' => 'required',
            'payment_method_id' => 'required',
        ]);

        $data = [
            'appointment_number' => $this->appointmentId ? Appointment::find($this->appointmentId)->appointment_number : Appointment::generateNumber(),
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'date' => $this->date,
            'global_shift_id' => $this->global_shift_id,
            'doctor_schedule_id' => $this->doctor_schedule_id,
            'time_slot' => $this->time_slot ?? '09:00:00',
            'priority' => $this->priority,
            'status' => $this->status,
            'doctor_fees' => $this->doctor_fees,
            'hospital_fees' => $this->hospital_fees,
            'discount_percentage' => $this->discount_percentage,
            'net_amount' => $this->net_amount,
            'payment_method_id' => $this->payment_method_id,
            'cheque_no' => $this->cheque_no,
            'cheque_date' => $this->cheque_date,
            'message' => $this->message,
            'live_consult' => $this->live_consult,
        ];

        Appointment::updateOrCreate(['id' => $this->appointmentId], $data);
        return redirect()->route('admin.appointment.index')->with('success', 'Appointment Processed.');
    }

    public function render()
    {
        return view('livewire.admin.appointment.manage', [
            'doctors' => Doctor::where('is_active', true)->get(),
            'shifts' => GlobalShift::all(),
            'paymentMethods' => PaymentMethod::where('is_active', true)->get(),
            'priorities' => AppointmentPriority::cases(),
            'statuses' => AppointmentStatus::cases(),
            'genders' => Gender::cases(),
            'blood_groups' => BloodGroup::cases(),
            'marital_statuses' => MaritalStatus::cases(),
        ]);
    }
}