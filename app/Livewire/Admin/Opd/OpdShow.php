<?php

namespace App\Livewire\Admin\Opd;

use App\Models\{
    OpdAdmission,
    OpdAdmissionCharge,
    OpdAdmissionPayment,
    OpdAdmissionSymptom,
    Charge,
    ChargeCategory,
    PaymentMethod,
    SymptomType,
    SymptomTitle,
    PathologyCategory,
    PathologyTest,
    RadiologyCategory,
    RadiologyTest,
    OpdPathologyTest,
    OpdRadiologyTest
};
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class OpdShow extends Component
{
    use WithFileUploads;

    public $opdId;
    public $activeTab = 'overview';

    // Modals Visibility
    public $showChargeModal = false, $showPaymentModal = false, $showSymptomModal = false;
    public $showPathologyModal = false, $showRadiologyModal = false, $showStatusModal = false;

    // Charge Properties
    public $charge_category_id, $charge_id;
    public $standard_charge = 0, $applied_charge = 0, $tax_percentage = 0, $tax_amount = 0, $net_amount = 0;

    // Payment Properties
    public $payment_method_id, $paid_amount = 0, $cheque_no, $cheque_date;

    // Symptom Properties
    public $symptom_type_id, $symptom_title_id, $new_symptom_title_name;

    // Test Properties
    public $pathology_category_id, $pathology_test_id;
    public $radiology_category_id, $radiology_test_id;

    // Status Update Properties
    public $editingTestType, $editingTestId, $newStatus;
    public $statusOptions = ['Pending', 'In Progress', 'Collected', 'Completed', 'Cancelled'];

    public function mount($id)
    {
        $this->opdId = $id;
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
        $this->standard_charge = $this->applied_charge = $this->tax_percentage = $this->tax_amount = $this->net_amount = 0;
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

    // --- Symptom Logic (With Quick Add) ---
    public function updatedSymptomTypeId()
    {
        $this->symptom_title_id = null;
    }
    public function createNewSymptomOption()
    {
        $this->validate(['symptom_type_id' => 'required', 'new_symptom_title_name' => 'required|string']);
        $newOption = SymptomTitle::create(['symptom_type_id' => $this->symptom_type_id, 'title' => $this->new_symptom_title_name]);
        $this->symptom_title_id = $newOption->id;
        $this->new_symptom_title_name = '';
        session()->flash('success', 'Symptom title added to master list.');
    }
    public function addSymptom()
    {
        $this->validate(['symptom_type_id' => 'required', 'symptom_title_id' => 'required']);
        OpdAdmissionSymptom::create(['opd_admission_id' => $this->opdId, 'symptom_type_id' => $this->symptom_type_id, 'symptom_title_id' => $this->symptom_title_id]);
        $this->reset(['showSymptomModal', 'symptom_type_id', 'symptom_title_id']);
        session()->flash('success', 'Symptom added.');
    }

    // --- Pathology & Radiology Logic ---
    public function updatedPathologyCategoryId()
    {
        $this->pathology_test_id = null;
    }
    public function addPathologyTest()
    {
        $this->validate(['pathology_test_id' => 'required']);
        $test = PathologyTest::with('charge.tax')->findOrFail($this->pathology_test_id);
        DB::transaction(function () use ($test) {
            OpdPathologyTest::create(['opd_admission_id' => $this->opdId, 'pathology_test_id' => $test->id, 'test_date' => now()]);
            $this->billInvestigation($test);
        });
        $this->reset(['showPathologyModal', 'pathology_category_id', 'pathology_test_id']);
        session()->flash('success', 'Pathology test added and billed.');
    }

    public function updatedRadiologyCategoryId()
    {
        $this->radiology_test_id = null;
    }
    public function addRadiologyTest()
    {
        $this->validate(['radiology_test_id' => 'required']);
        $test = RadiologyTest::with('charge.tax')->findOrFail($this->radiology_test_id);
        DB::transaction(function () use ($test) {
            OpdRadiologyTest::create(['opd_admission_id' => $this->opdId, 'radiology_test_id' => $test->id, 'test_date' => now()]);
            $this->billInvestigation($test);
        });
        $this->reset(['showRadiologyModal', 'radiology_category_id', 'radiology_test_id']);
        session()->flash('success', 'Radiology test added and billed.');
    }

    private function billInvestigation($test)
    {
        if ($test->charge) {
            $tax = ($test->charge->standard_charge * ($test->charge->tax->percentage ?? 0)) / 100;
            OpdAdmissionCharge::create([
                'opd_admission_id' => $this->opdId,
                'charge_id' => $test->charge_id,
                'standard_charge' => $test->charge->standard_charge,
                'applied_charge' => $test->charge->standard_charge,
                'tax_percentage' => $test->charge->tax->percentage ?? 0,
                'tax_amount' => $tax,
                'net_amount' => $test->charge->standard_charge + $tax,
            ]);
        }
    }

    // --- Status Edit Logic ---
    public function editTestStatus($type, $id)
    {
        $this->editingTestType = $type;
        $this->editingTestId = $id;
        $record = $type === 'pathology' ? OpdPathologyTest::findOrFail($id) : OpdRadiologyTest::findOrFail($id);
        $this->newStatus = $record->status;
        $this->showStatusModal = true;
    }
    public function updateTestStatus()
    {
        $model = $this->editingTestType === 'pathology' ? OpdPathologyTest::class : OpdRadiologyTest::class;
        $model::where('id', $this->editingTestId)->update(['status' => $this->newStatus]);
        $this->reset(['showStatusModal', 'editingTestType', 'editingTestId', 'newStatus']);
        session()->flash('success', 'Investigation status updated.');
    }

    // --- Payment Logic ---
    public function addPayment()
    {
        $this->validate(['paid_amount' => 'required|numeric|min:1', 'payment_method_id' => 'required']);
        OpdAdmissionPayment::create(['opd_admission_id' => $this->opdId, 'payment_method_id' => $this->payment_method_id, 'paid_amount' => $this->paid_amount, 'cheque_no' => $this->cheque_no, 'cheque_date' => $this->cheque_date]);
        $this->reset(['showPaymentModal', 'paid_amount', 'cheque_no', 'cheque_date']);
        session()->flash('success', 'Payment recorded.');
    }

    public function render()
    {
        $opd = OpdAdmission::with(['patient.user', 'doctor', 'charges.chargeMaster', 'payments.method', 'symptoms.type', 'symptoms.title', 'pathologyTests.test.category', 'radiologyTests.test.category'])->findOrFail($this->opdId);
        return view('livewire.admin.opd.opd-show', [
            'opd' => $opd,
            'categories' => ChargeCategory::all(),
            'charges' => Charge::where('charge_category_id', $this->charge_category_id)->get(),
            'paymentMethods' => PaymentMethod::where('is_active', true)->get(),
            'symptomTypes' => SymptomType::all(),
            'symptomTitles' => SymptomTitle::where('symptom_type_id', $this->symptom_type_id)->get(),
            'pathologyCategories' => PathologyCategory::all(),
            'pathologyTests' => PathologyTest::where('pathology_category_id', $this->pathology_category_id)->get(),
            'radiologyCategories' => RadiologyCategory::all(),
            'radiologyTests' => RadiologyTest::where('radiology_category_id', $this->radiology_category_id)->get(),
        ]);
    }
}
