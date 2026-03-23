<?php

namespace App\Livewire\Admin\Ipd;

use App\Models\{IpdAdmission, IpdAdmissionCharge, IpdAdmissionPayment, IpdAdmissionSymptom, Patient, Doctor, SymptomType, SymptomTitle, PaymentMethod, Charge, ChargeCategory, Floor, BedGroup, Bed};
use Livewire\{Component, WithFileUploads};
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class IpdManage extends Component
{
    use WithFileUploads;

    public $ipdId;
    public $patient_id, $doctor_id, $admission_date, $case_type = 'Normal', $is_casualty = false;
    public $refference, $symptoms_description, $note, $known_allergies;

    // Bed Selection
    public $floor_id, $bed_group_id, $bed_id;

    // Symptoms & Financials
    public $temp_type_id, $temp_title_id, $added_symptoms = [];
    public $doctor_fee = 0, $hospital_fee = 0, $charge_category_id, $charge_id, $extra_charge_amount = 0;
    public $tax_percentage = 0, $tax_amount = 0, $discount_percentage = 0, $discount_amount = 0, $net_amount = 0, $paid_amount = 0;
    public $payment_method_id;

    public $patient_search = '', $patient_results = [], $selected_patient_data = null, $showPatientModal = false;

    protected $rules = [
        'patient_id' => 'required',
        'doctor_id' => 'required',
        'bed_id' => 'required',
        'admission_date' => 'required',
        'payment_method_id' => 'required',
        'paid_amount' => 'required|numeric|min:0',
    ];

    public function mount($id = null)
    {
        $this->admission_date = now()->format('Y-m-d\TH:i');
        $this->payment_method_id = PaymentMethod::where('is_default', true)->first()?->id;

        if ($id) {
            $this->ipdId = $id;
            $ipd = IpdAdmission::with(['patient.user', 'symptoms.type', 'symptoms.title', 'charges', 'bed.bedGroup'])->findOrFail($id);

            $this->patient_id = $ipd->patient_id;
            $this->doctor_id = $ipd->doctor_id;
            $this->bed_id = $ipd->bed_id;
            $this->bed_group_id = $ipd->bed->bed_group_id;
            $this->floor_id = $ipd->bed->bedGroup->floor_id;
            $this->admission_date = $ipd->admission_date->format('Y-m-d\TH:i');
            $this->selectPatient($ipd->patient_id);
            // ... (populate other fields similar to OPD)
        }
    }

    // Bed Cascading Logic
    public function updatedFloorId()
    {
        $this->bed_group_id = null;
        $this->bed_id = null;
    }
    public function updatedBedGroupId()
    {
        $this->bed_id = null;
    }

    public function updatedDoctorId($id)
    {
        if ($id) {
            $doctor = Doctor::find($id);
            $this->doctor_fee = $doctor->consultation_fee ?? 0;
            $this->hospital_fee = $doctor->ipd_hospital_fee ?? 0; // Ensure this column exists in Doctor model
        }
        $this->calculateTotals();
    }

    public function updatedChargeId($id)
    {
        if ($id) {
            $charge = Charge::with('tax')->find($id);
            $this->extra_charge_amount = $charge->standard_charge;
            $this->tax_percentage = $charge->tax->percentage ?? 0;
            $this->tax_amount = ($this->extra_charge_amount * $this->tax_percentage) / 100;
        }
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $subtotal = (float)$this->doctor_fee + (float)$this->hospital_fee + (float)$this->extra_charge_amount + (float)$this->tax_amount;
        $this->discount_amount = ($subtotal * (float)($this->discount_percentage ?? 0)) / 100;
        $this->net_amount = $subtotal - $this->discount_amount;
        $this->paid_amount = $this->net_amount;
    }

    // ... (Add Symptom, Remove Symptom, Patient Search logic exactly like OPDManage) ...
    public function updatedPatientSearch($query)
    {
        if (strlen($query) < 2) {
            $this->patient_results = [];
            return;
        }
        $this->patient_results = Patient::with('user')->whereHas('user', fn($u) => $u->where('name', 'like', "%$query%"))->orWhere('mrn_number', 'like', "%$query%")->limit(5)->get();
    }

    public function selectPatient($id)
    {
        $this->selected_patient_data = Patient::with('user')->find($id);
        $this->patient_id = $id;
        $this->patient_search = $this->selected_patient_data->user->name;
        $this->patient_results = [];
    }

    public function addSymptom()
    {
        $this->validate(['temp_type_id' => 'required', 'temp_title_id' => 'required']);
        $type = SymptomType::find($this->temp_type_id);
        $title = SymptomTitle::find($this->temp_title_id);
        $this->added_symptoms[] = ['type_id' => $type->id, 'type_name' => $type->name, 'title_id' => $title->id, 'title_name' => $title->title];
        $this->reset(['temp_type_id', 'temp_title_id']);
    }

    public function save()
    {
        $this->validate();
        try {
            DB::beginTransaction();
            $admission = IpdAdmission::create([
                'ipd_number' => IpdAdmission::generateIpdNumber(),
                'patient_id' => $this->patient_id,
                'doctor_id' => $this->doctor_id,
                'bed_id' => $this->bed_id,
                'admission_date' => $this->admission_date,
                'case_type' => $this->case_type,
                'is_casualty' => $this->is_casualty,
                'symptoms_description' => $this->symptoms_description,
                'doctor_fee' => $this->doctor_fee,
                'hospital_fee' => $this->hospital_fee,
                'discount_percentage' => $this->discount_percentage,
                'discount_amount' => $this->discount_amount,
                'net_amount' => $this->net_amount,
                'status' => 'admitted'
            ]);

            foreach ($this->added_symptoms as $s) {
                IpdAdmissionSymptom::create(['ipd_admission_id' => $admission->id, 'symptom_type_id' => $s['type_id'], 'symptom_title_id' => $s['title_id']]);
            }

            if ($this->charge_id) {
                IpdAdmissionCharge::create([
                    'ipd_admission_id' => $admission->id,
                    'charge_id' => $this->charge_id,
                    'standard_charge' => $this->extra_charge_amount,
                    'applied_charge' => $this->extra_charge_amount,
                    'tax_percentage' => $this->tax_percentage,
                    'tax_amount' => $this->tax_amount,
                    'net_amount' => $this->extra_charge_amount + $this->tax_amount
                ]);
            }

            IpdAdmissionPayment::create(['ipd_admission_id' => $admission->id, 'payment_method_id' => $this->payment_method_id, 'paid_amount' => $this->paid_amount]);

            DB::commit();
            return redirect()->route('admin.ipd.index')->with('success', 'IPD Admission Successful.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.ipd.ipd-manage', [
            'doctors' => Doctor::where('is_active', true)->get(),
            'floors' => Floor::all(),
            'bedGroups' => BedGroup::where('floor_id', $this->floor_id)->get(),
            'availableBeds' => Bed::where('bed_group_id', $this->bed_group_id)->where('is_active', true)->get(),
            'symptomTypes' => SymptomType::all(),
            'symptomTitles' => SymptomTitle::where('symptom_type_id', $this->temp_type_id)->get(),
            'categories' => ChargeCategory::all(),
            'charges' => Charge::where('charge_category_id', $this->charge_category_id)->get(),
            'paymentMethods' => PaymentMethod::where('is_active', true)->get(),
        ]);
    }
}
