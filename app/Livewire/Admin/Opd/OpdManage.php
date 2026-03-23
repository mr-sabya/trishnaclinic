<?php

namespace App\Livewire\Admin\Opd;

use App\Models\{OpdAdmission, OpdAdmissionCharge, OpdAdmissionPayment, OpdAdmissionSymptom, Patient, Doctor, SymptomType, SymptomTitle, PaymentMethod, Charge, ChargeCategory};
use Livewire\{Component, WithFileUploads};
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class OpdManage extends Component
{
    use WithFileUploads;

    public $opdId;
    public $patient_id, $doctor_id, $appointment_date, $case_type = 'New Case', $is_casualty = false;
    public $refference, $symptoms_description, $note, $known_allergies;

    public $temp_type_id, $temp_title_id, $added_symptoms = [];

    public $doctor_fee = 0, $hospital_fee = 0;
    public $charge_category_id, $charge_id, $extra_charge_amount = 0;
    public $tax_percentage = 0, $tax_amount = 0;
    public $discount_percentage = 0, $discount_amount = 0, $net_amount = 0, $paid_amount = 0;
    public $payment_method_id;

    public $patient_search = '', $patient_results = [], $selected_patient_data = null, $showPatientModal = false;

    protected $rules = [
        'patient_id' => 'required',
        'doctor_id' => 'required',
        'appointment_date' => 'required',
        'payment_method_id' => 'required',
        'paid_amount' => 'required|numeric|min:0',
        // Charge and Symptoms removed from required rules
    ];

    protected $messages = [
        'patient_id.required' => 'Please search and select a patient.',
        'doctor_id.required' => 'Select a consultant doctor.',
        'payment_method_id.required' => 'Select a payment method.',
    ];

    public function mount($id = null)
    {
        $this->appointment_date = now()->format('Y-m-d\TH:i');
        $this->payment_method_id = PaymentMethod::where('is_default', true)->first()?->id;

        if ($id) {
            $this->opdId = $id;
            $opd = OpdAdmission::with(['patient.user', 'symptoms.type', 'symptoms.title', 'charges.chargeMaster'])->findOrFail($id);

            $this->patient_id = $opd->patient_id;
            $this->doctor_id = $opd->doctor_id;
            $this->appointment_date = $opd->appointment_date->format('Y-m-d\TH:i');
            $this->case_type = $opd->case_type;
            $this->is_casualty = $opd->is_casualty;
            $this->refference = $opd->refference;
            $this->symptoms_description = $opd->symptoms_description;
            $this->known_allergies = $opd->known_allergies;
            $this->note = $opd->note;

            $this->selectPatient($opd->patient_id);

            foreach ($opd->symptoms as $symptom) {
                $this->added_symptoms[] = [
                    'type_id' => $symptom->symptom_type_id,
                    'type_name' => $symptom->type->name,
                    'title_id' => $symptom->symptom_title_id,
                    'title_name' => $symptom->title->title
                ];
            }

            $this->doctor_fee = $opd->doctor_fee;
            $this->hospital_fee = $opd->hospital_fee;
            $this->discount_percentage = $opd->discount_percentage;
            $this->discount_amount = $opd->discount_amount;
            $this->net_amount = $opd->net_amount;

            $extraCharge = $opd->charges->whereNotNull('charge_id')->first();
            if ($extraCharge) {
                $this->charge_category_id = $extraCharge->chargeMaster->charge_category_id;
                $this->charge_id = $extraCharge->charge_id;
                $this->extra_charge_amount = $extraCharge->standard_charge;
                $this->tax_percentage = $extraCharge->tax_percentage ?? 0;
                $this->tax_amount = $extraCharge->tax_amount ?? 0;
            }

            $payment = $opd->payments->first();
            if ($payment) {
                $this->payment_method_id = $payment->payment_method_id;
                $this->paid_amount = $payment->paid_amount;
            }
        }
    }

    public function updatedDoctorId($id)
    {
        if ($id) {
            $doctor = Doctor::find($id);
            $this->doctor_fee = $doctor->consultation_fee ?? 0;
            $this->hospital_fee = $doctor->opd_hospital_fee ?? 0;
        } else {
            $this->doctor_fee = 0;
            $this->hospital_fee = 0;
        }
        $this->calculateTotals();
    }

    public function updatedChargeCategoryId()
    {
        $this->charge_id = null;
        $this->extra_charge_amount = 0;
        $this->tax_amount = 0;
        $this->calculateTotals();
    }

    public function updatedChargeId($id)
    {
        if ($id) {
            $charge = Charge::with('tax')->find($id);
            if ($charge) {
                $this->extra_charge_amount = $charge->standard_charge;
                $this->tax_percentage = $charge->tax->percentage ?? 0;
                $this->tax_amount = ($this->extra_charge_amount * $this->tax_percentage) / 100;
            }
        } else {
            $this->extra_charge_amount = 0;
            $this->tax_percentage = 0;
            $this->tax_amount = 0;
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

    public function updatedDiscountPercentage()
    {
        $this->calculateTotals();
    }

    public function addSymptom()
    {
        $this->validate(['temp_type_id' => 'required', 'temp_title_id' => 'required']);
        $type = SymptomType::find($this->temp_type_id);
        $title = SymptomTitle::find($this->temp_title_id);

        $this->added_symptoms[] = [
            'type_id' => $type->id,
            'type_name' => $type->name,
            'title_id' => $title->id,
            'title_name' => $title->title
        ];
        $this->reset(['temp_type_id', 'temp_title_id']);
    }

    public function removeSymptom($index)
    {
        unset($this->added_symptoms[$index]);
        $this->added_symptoms = array_values($this->added_symptoms);
    }

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

    #[On('patientCreated')]
    public function handlePatientCreated($id, $name)
    {
        $this->selectPatient($id);
        $this->showPatientModal = false;
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $admission = OpdAdmission::create([
                'opd_number' => OpdAdmission::generateOpdNumber(),
                'patient_id' => $this->patient_id,
                'doctor_id' => $this->doctor_id,
                'appointment_date' => $this->appointment_date,
                'case_type' => $this->case_type,
                'is_casualty' => $this->is_casualty,
                'symptoms_description' => $this->symptoms_description,
                'note' => $this->note,
                'known_allergies' => $this->known_allergies,
                'doctor_fee' => $this->doctor_fee,
                'hospital_fee' => $this->hospital_fee,
                'discount_percentage' => $this->discount_percentage,
                'discount_amount' => $this->discount_amount,
                'net_amount' => $this->net_amount,
                'status' => 'admitted'
            ]);

            // Optional Symptoms
            if (!empty($this->added_symptoms)) {
                foreach ($this->added_symptoms as $s) {
                    OpdAdmissionSymptom::create([
                        'opd_admission_id' => $admission->id,
                        'symptom_type_id' => $s['type_id'],
                        'symptom_title_id' => $s['title_id']
                    ]);
                }
            }

            // Optional Service Charge
            if ($this->charge_id) {
                OpdAdmissionCharge::create([
                    'opd_admission_id' => $admission->id,
                    'charge_id' => $this->charge_id,
                    'standard_charge' => $this->extra_charge_amount,
                    'applied_charge' => $this->extra_charge_amount,
                    'tax_percentage' => $this->tax_percentage,
                    'tax_amount' => $this->tax_amount,
                    'net_amount' => $this->extra_charge_amount + $this->tax_amount
                ]);
            }

            // Payment (Still required as per your rules, but can be 0)
            OpdAdmissionPayment::create([
                'opd_admission_id' => $admission->id,
                'payment_method_id' => $this->payment_method_id,
                'paid_amount' => $this->paid_amount
            ]);

            DB::commit();
            return redirect()->route('admin.opd.index')->with('success', 'OPD Admission Successful.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.opd.opd-manage', [
            'doctors' => Doctor::where('is_active', true)->get(),
            'symptomTypes' => SymptomType::all(),
            'symptomTitles' => SymptomTitle::where('symptom_type_id', $this->temp_type_id)->get(),
            'categories' => ChargeCategory::all(),
            'charges' => Charge::where('charge_category_id', $this->charge_category_id)->get(),
            'paymentMethods' => PaymentMethod::where('is_active', true)->get(),
        ]);
    }
}
