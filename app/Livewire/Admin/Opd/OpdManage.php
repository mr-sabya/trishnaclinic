<?php

namespace App\Livewire\Admin\Opd;

use App\Models\{OpdAdmission, OpdAdmissionCharge, OpdAdmissionPayment, OpdAdmissionSymptom, Patient, Doctor, SymptomType, SymptomTitle, Charge, ChargeCategory, PaymentMethod};
use Livewire\{Component, WithFileUploads};
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class OpdManage extends Component
{
    use WithFileUploads;

    public $opdId;

    // --- Header & Clinical ---
    public $patient_id, $doctor_id, $appointment_date, $case_type = 'New Case', $is_casualty = false;
    public $refference, $symptoms_description, $note, $known_allergies;

    // --- Symptom Adder State ---
    public $temp_type_id, $temp_title_id;
    public $added_symptoms = []; // Stores objects like ['type_id' => 1, 'title_id' => 5, 'title_name' => 'Fever']

    // --- Financials ---
    public $charge_category_id, $charge_id;
    public $standard_charge = 0, $applied_charge = 0, $tax_percentage = 0, $discount_percentage = 0, $net_amount = 0;

    // --- Payment ---
    public $payment_method_id, $paid_amount = 0, $cheque_no, $cheque_date;

    // --- UI Search State ---
    public $patient_search = '', $patient_results = [], $selected_patient_data = null, $showPatientModal = false;

    public function mount($id = null)
    {
        $this->appointment_date = now()->format('Y-m-d\TH:i');
        $this->payment_method_id = PaymentMethod::where('is_default', true)->first()?->id;

        if ($id) {
            $this->opdId = $id;
            $opd = OpdAdmission::with('symptoms.type', 'symptoms.title')->findOrFail($id);
            $this->fill($opd->toArray());
            $this->selectPatient($opd->patient_id);

            foreach ($opd->symptoms as $s) {
                $this->added_symptoms[] = [
                    'type_id' => $s->symptom_type_id,
                    'type_name' => $s->type->name,
                    'title_id' => $s->symptom_title_id,
                    'title_name' => $s->title->title
                ];
            }
        }
    }

    // --- Symptom Adder Logic ---
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

    // --- Patient Search Logic ---
    public function updatedPatientSearch($query)
    {
        if (strlen($query) < 2) {
            $this->patient_results = [];
            return;
        }
        $this->patient_results = Patient::with('user')
            ->whereHas('user', fn($u) => $u->where('name', 'like', "%$query%")->orWhere('phone', 'like', "%$query%"))
            ->orWhere('mrn_number', 'like', "%$query%")->limit(5)->get();
    }

    public function selectPatient($id)
    {
        $this->selected_patient_data = Patient::with(['user', 'tpa'])->find($id);
        $this->patient_id = $id;
        $this->patient_search = $this->selected_patient_data->user->name;
        $this->known_allergies = $this->selected_patient_data->known_allergies;
        $this->patient_results = [];
    }

    // --- Financial Logic ---
    public function updatedChargeId($id)
    {
        if ($id) {
            $charge = Charge::with('tax_category')->find($id);
            $this->standard_charge = $charge->standard_charge;
            $this->applied_charge = $charge->standard_charge;
            $this->tax_percentage = $charge->tax_category?->percentage ?? 0;
            $this->calculateTotals();
        }
    }

    public function calculateTotals()
    {
        $discount = ($this->applied_charge * $this->discount_percentage) / 100;
        $afterDiscount = $this->applied_charge - $discount;
        $tax = ($afterDiscount * $this->tax_percentage) / 100;
        $this->net_amount = $afterDiscount + $tax;
        $this->paid_amount = $this->net_amount;
    }

    #[On('patientCreated')]
    public function handlePatientCreated($id, $name)
    {
        $this->selectPatient($id);
        $this->showPatientModal = false;
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->showPatientModal = false;
    }

    public function save()
    {
        $this->validate([
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'charge_id' => 'required',
            'paid_amount' => 'required|numeric',
            'added_symptoms' => 'required|array|min:1'
        ]);

        DB::transaction(function () {
            $admission = OpdAdmission::updateOrCreate(['id' => $this->opdId], [
                'opd_number' => $this->opdId ? OpdAdmission::find($this->opdId)->opd_number : OpdAdmission::generateOpdNumber(),
                'patient_id' => $this->patient_id,
                'doctor_id' => $this->doctor_id,
                'appointment_date' => $this->appointment_date,
                'case_type' => $this->case_type,
                'is_casualty' => $this->is_casualty,
                'symptoms_description' => $this->symptoms_description,
                'note' => $this->note,
                'known_allergies' => $this->known_allergies,
            ]);

            // Save Relational Symptoms
            OpdAdmissionSymptom::where('opd_admission_id', $admission->id)->delete();
            foreach ($this->added_symptoms as $s) {
                OpdAdmissionSymptom::create([
                    'opd_admission_id' => $admission->id,
                    'symptom_type_id' => $s['type_id'],
                    'symptom_title_id' => $s['title_id']
                ]);
            }

            // Save Charges & Payments
            OpdAdmissionCharge::updateOrCreate(['opd_admission_id' => $admission->id], [
                'charge_id' => $this->charge_id,
                'standard_charge' => $this->standard_charge,
                'applied_charge' => $this->applied_charge,
                'tax_percentage' => $this->tax_percentage,
                'discount_percentage' => $this->discount_percentage,
                'net_amount' => $this->net_amount,
            ]);

            OpdAdmissionPayment::create([
                'opd_admission_id' => $admission->id,
                'payment_method_id' => $this->payment_method_id,
                'paid_amount' => $this->paid_amount,
                'cheque_no' => $this->cheque_no,
                'cheque_date' => $this->cheque_date,
            ]);
        });

        return redirect()->route('admin.opd.index')->with('success', 'Admission Finalized.');
    }

    public function render()
    {
        return view('livewire.admin.opd.opd-manage', [
            'doctors' => Doctor::all(),
            'symptomTypes' => SymptomType::all(),
            'symptomTitles' => SymptomTitle::where('symptom_type_id', $this->temp_type_id)->get(),
            'chargeCategories' => ChargeCategory::all(),
            'charges' => Charge::where('charge_category_id', $this->charge_category_id)->get(),
            'paymentMethods' => PaymentMethod::getActiveMethods(),
        ]);
    }
}
