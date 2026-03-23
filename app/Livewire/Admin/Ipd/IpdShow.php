<?php

namespace App\Livewire\Admin\Ipd;

use App\Models\{IpdAdmission, IpdAdmissionCharge, IpdAdmissionPayment, Charge, ChargeCategory, PaymentMethod};
use Livewire\Component;
use Livewire\WithFileUploads;

class IpdShow extends Component
{
    use WithFileUploads;

    public $ipdId;
    public $activeTab = 'overview';

    // Charge Modal Properties
    public $showChargeModal = false;
    public $charge_category_id, $charge_id;
    public $standard_charge = 0, $applied_charge = 0, $tax_percentage = 0, $tax_amount = 0, $net_amount = 0;

    // Payment Modal Properties
    public $showPaymentModal = false;
    public $payment_method_id, $paid_amount = 0, $cheque_no, $cheque_date, $note;

    public function mount($id)
    {
        $this->ipdId = $id;
        $this->payment_method_id = PaymentMethod::where('is_default', true)->first()?->id;
    }

    // --- Charge Logic ---
    public function updatedChargeCategoryId()
    {
        $this->charge_id = null;
        $this->resetChargeFields();
    }

    public function updatedChargeId($id)
    {
        if ($id) {
            $charge = Charge::with('tax')->find($id);
            if ($charge) {
                $this->standard_charge = $charge->standard_charge;
                $this->applied_charge = $charge->standard_charge;
                $this->tax_percentage = $charge->tax->percentage ?? 0;
                $this->calculateChargeNet();
            }
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
        $this->validate([
            'charge_id' => 'required',
            'applied_charge' => 'required|numeric|min:0',
        ]);

        IpdAdmissionCharge::create([
            'ipd_admission_id' => $this->ipdId,
            'charge_id' => $this->charge_id,
            'standard_charge' => $this->standard_charge,
            'applied_charge' => $this->applied_charge,
            'tax_percentage' => $this->tax_percentage,
            'tax_amount' => $this->tax_amount,
            'net_amount' => $this->net_amount,
        ]);

        $this->reset(['showChargeModal', 'charge_id', 'charge_category_id']);
        $this->resetChargeFields();
        session()->flash('success', 'Service charge added to IPD bill.');
    }

    // --- Payment Logic ---
    public function addPayment()
    {
        $this->validate([
            'paid_amount' => 'required|numeric|min:1',
            'payment_method_id' => 'required'
        ]);

        IpdAdmissionPayment::create([
            'ipd_admission_id' => $this->ipdId,
            'payment_method_id' => $this->payment_method_id,
            'paid_amount' => $this->paid_amount,
            'cheque_no' => $this->cheque_no,
            'cheque_date' => $this->cheque_date,
            'note' => $this->note,
        ]);

        $this->reset(['showPaymentModal', 'paid_amount', 'cheque_no', 'cheque_date', 'note']);
        session()->flash('success', 'Deposit payment recorded successfully.');
    }

    public function render()
    {
        $ipd = IpdAdmission::with([
            'patient.user',
            'doctor',
            'bed.bedGroup.floor',
            'charges.chargeMaster',
            'payments.method',
            'symptoms.type',
            'symptoms.title'
        ])->findOrFail($this->ipdId);

        return view('livewire.admin.ipd.ipd-show', [
            'ipd' => $ipd,
            'categories' => ChargeCategory::all(),
            'charges' => Charge::where('charge_category_id', $this->charge_category_id)->get(),
            'paymentMethods' => PaymentMethod::where('is_active', true)->get(),
        ]);
    }
}
