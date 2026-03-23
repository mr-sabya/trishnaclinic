<?php

namespace App\Livewire\Admin\Opd;

// Add these models to your use statements
use App\Models\{OpdAdmission, OpdAdmissionCharge, OpdAdmissionPayment, OpdAdmissionSymptom, Charge, ChargeCategory, PaymentMethod, SymptomType, SymptomTitle};
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class OpdShow extends Component
{
    use WithFileUploads;

    public $opdId;
    public $activeTab = 'overview';

    // New Charge Modal Properties
    public $showChargeModal = false;
    public $charge_category_id, $charge_id;
    public $standard_charge = 0, $applied_charge = 0, $tax_percentage = 0, $tax_amount = 0, $net_amount = 0;

    // New Payment Modal Properties
    public $showPaymentModal = false;
    public $payment_method_id, $paid_amount = 0, $cheque_no, $cheque_date;

    // --- ADDED: Symptom Properties ---
    public $showSymptomModal = false;
    public $symptom_type_id, $symptom_title_id;

    public function mount($id)
    {
        $this->opdId = $id;
        $this->payment_method_id = PaymentMethod::where('is_default', true)->first()?->id;
    }

    // --- Charge Logic (Keep existing) ---
    public function updatedChargeCategoryId()
    {
        $this->charge_id = null;
        $this->resetChargeFields();
    }

    public function updatedChargeId($id)
    {
        if ($id) {
            $charge = Charge::with('tax')->find($id);
            $this->standard_charge = $charge->standard_charge;
            $this->applied_charge = $charge->standard_charge;
            $this->tax_percentage = $charge->tax?->percentage ?? 0;
            $this->calculateChargeNet();
        } else {
            $this->resetChargeFields();
        }
    }

    public function updatedAppliedCharge()
    {
        $this->calculateChargeNet();
    }

    public function calculateChargeNet()
    {
        $this->tax_amount = ($this->applied_charge * $this->tax_percentage) / 100;
        $this->net_amount = (float)$this->applied_charge + (float)$this->tax_amount;
    }

    private function resetChargeFields()
    {
        $this->standard_charge = 0;
        $this->applied_charge = 0;
        $this->tax_percentage = 0;
        $this->tax_amount = 0;
        $this->net_amount = 0;
    }

    public function addCharge()
    {
        $this->validate(['charge_id' => 'required', 'applied_charge' => 'required|numeric|min:0']);
        OpdAdmissionCharge::create([
            'opd_admission_id' => $this->opdId,
            'charge_id' => $this->charge_id,
            'standard_charge' => $this->standard_charge,
            'applied_charge' => $this->applied_charge,
            'tax_percentage' => $this->tax_percentage,
            'tax_amount' => $this->tax_amount,
            'net_amount' => $this->net_amount,
        ]);
        $this->reset(['showChargeModal', 'charge_id', 'charge_category_id']);
        $this->resetChargeFields();
        session()->flash('success', 'Charge added successfully.');
    }

    // --- Payment Logic (Keep existing) ---
    public function addPayment()
    {
        $this->validate(['paid_amount' => 'required|numeric|min:1', 'payment_method_id' => 'required']);
        OpdAdmissionPayment::create([
            'opd_admission_id' => $this->opdId,
            'payment_method_id' => $this->payment_method_id,
            'paid_amount' => $this->paid_amount,
            'cheque_no' => $this->cheque_no,
            'cheque_date' => $this->cheque_date,
        ]);
        $this->reset(['showPaymentModal', 'paid_amount', 'cheque_no', 'cheque_date']);
        session()->flash('success', 'Payment recorded.');
    }

    // --- ADDED: Symptom Logic ---
    public function updatedSymptomTypeId()
    {
        $this->symptom_title_id = null;
    }

    public function addSymptom()
    {
        $this->validate([
            'symptom_type_id' => 'required',
            'symptom_title_id' => 'required'
        ]);

        OpdAdmissionSymptom::create([
            'opd_admission_id' => $this->opdId,
            'symptom_type_id' => $this->symptom_type_id,
            'symptom_title_id' => $this->symptom_title_id,
        ]);

        $this->reset(['showSymptomModal', 'symptom_type_id', 'symptom_title_id']);
        session()->flash('success', 'Symptom added successfully.');
    }

    public function deleteSymptom($id)
    {
        OpdAdmissionSymptom::destroy($id);
        session()->flash('success', 'Symptom removed.');
    }

    public function render()
    {
        // Load symptoms relationship as well
        $opd = OpdAdmission::with(['patient.user', 'doctor', 'charges.chargeMaster', 'payments.method', 'symptoms.type', 'symptoms.title'])->findOrFail($this->opdId);

        return view('livewire.admin.opd.opd-show', [
            'opd' => $opd,
            'categories' => ChargeCategory::all(),
            'charges' => Charge::where('charge_category_id', $this->charge_category_id)->get(),
            'paymentMethods' => PaymentMethod::where('is_active', true)->get(),
            // ADDED: Lists for Symptom Modals
            'symptomTypes' => SymptomType::all(),
            'symptomTitles' => SymptomTitle::where('symptom_type_id', $this->symptom_type_id)->get(),
        ]);
    }
}
