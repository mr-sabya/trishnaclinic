<?php

namespace App\Livewire\Admin\Opd;

use App\Models\{OpdAdmission, OpdAdmissionCharge, OpdAdmissionPayment, Charge, ChargeCategory, PaymentMethod};
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
    public $charge_category_id, $charge_id, $standard_charge = 0, $applied_charge = 0, $tax_percentage = 0, $net_amount = 0;

    // New Payment Modal Properties
    public $showPaymentModal = false;
    public $payment_method_id, $paid_amount = 0, $cheque_no, $cheque_date;

    public function mount($id)
    {
        $this->opdId = $id;
        $this->payment_method_id = PaymentMethod::where('is_default', true)->first()?->id;
    }

    // --- Charge Logic ---
    public function updatedChargeId($id)
    {
        if ($id) {
            $charge = Charge::with('tax_category')->find($id);
            $this->standard_charge = $charge->standard_charge;
            $this->applied_charge = $charge->standard_charge;
            $this->tax_percentage = $charge->tax_category?->percentage ?? 0;
            $this->calculateChargeNet();
        }
    }

    public function calculateChargeNet()
    {
        $tax = ($this->applied_charge * $this->tax_percentage) / 100;
        $this->net_amount = $this->applied_charge + $tax;
    }

    public function addCharge()
    {
        $this->validate(['charge_id' => 'required', 'applied_charge' => 'required|numeric']);

        OpdAdmissionCharge::create([
            'opd_admission_id' => $this->opdId,
            'charge_id' => $this->charge_id,
            'standard_charge' => $this->standard_charge,
            'applied_charge' => $this->applied_charge,
            'tax_percentage' => $this->tax_percentage,
            'net_amount' => $this->net_amount,
        ]);

        $this->reset(['showChargeModal', 'charge_id', 'applied_charge', 'net_amount']);
        session()->flash('success', 'Charge added successfully.');
    }

    // --- Payment Logic ---
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

    public function render()
    {
        $opd = OpdAdmission::with(['patient.user', 'doctor', 'charges.chargeMaster', 'payments.method'])->findOrFail($this->opdId);

        return view('livewire.admin.opd.opd-show', [
            'opd' => $opd,
            'categories' => ChargeCategory::all(),
            'charges' => Charge::where('charge_category_id', $this->charge_category_id)->get(),
            'paymentMethods' => PaymentMethod::getActiveMethods(),
        ]);
    }
}
