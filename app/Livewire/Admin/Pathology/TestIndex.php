<?php

namespace App\Livewire\Admin\Pathology;

use App\Models\PathologyTest;
use App\Models\PathologyCategory;
use App\Models\PathologyParameter;
use App\Models\Charge;
use App\Models\ChargeCategory;
use Livewire\Component;
use Livewire\WithPagination;

class TestIndex extends Component
{
    use WithPagination;

    // DataTable State (These MUST be public to be seen by Blade)
    public $search = '';
    public $perPage = 10;
    public $sortBy = 'test_name'; // <--- Added this
    public $sortDir = 'ASC';      // <--- Added this

    // Modal & Form State
    public $showModal = false;
    public $testId = null;

    // Main Fields
    public $test_name, $short_name, $test_type, $pathology_category_id;
    public $sub_category, $method, $report_days = 0;

    // Charge logic
    public $charge_category_id;
    public $charge_id;
    public $tax = 0, $standard_charge = 0, $amount = 0;

    // Dynamic Parameter Rows
    public $testParameters = [];

    protected $paginationTheme = 'bootstrap';

    // Added this method to handle header clicks
    public function setSortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDir = ($this->sortDir === 'ASC') ? 'DESC' : 'ASC';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'ASC';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        if (empty($this->testParameters)) {
            $this->addParameterRow();
        }
    }

    public function addParameterRow()
    {
        $this->testParameters[] = [
            'parameter_id' => '',
            'reference_range' => '',
            'unit' => ''
        ];
    }

    public function removeParameterRow($index)
    {
        unset($this->testParameters[$index]);
        $this->testParameters = array_values($this->testParameters);
        if (empty($this->testParameters)) {
            $this->addParameterRow();
        }
    }

    public function updatedTestParameters($value, $key)
    {
        if (str_contains($key, 'parameter_id')) {
            $parts = explode('.', $key);
            $index = $parts[0];

            if ($value) {
                $param = PathologyParameter::with('unit')->find($value);
                if ($param) {
                    $this->testParameters[$index]['reference_range'] = ($param->reference_range_from ?? '') . ' - ' . ($param->reference_range_to ?? '');
                    $this->testParameters[$index]['unit'] = $param->unit->name ?? '';
                }
            } else {
                $this->testParameters[$index]['reference_range'] = '';
                $this->testParameters[$index]['unit'] = '';
            }
        }
    }

    public function updatedChargeId($id)
    {
        if ($id) {
            $charge = Charge::with('tax')->find($id);
            if ($charge) {
                $this->standard_charge = $charge->standard_charge;
                $this->tax = $charge->tax->percentage ?? 0;
                $this->amount = $this->standard_charge + ($this->standard_charge * $this->tax / 100);
            }
        } else {
            $this->reset(['standard_charge', 'tax', 'amount']);
        }
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['testId', 'test_name', 'short_name', 'test_type', 'pathology_category_id', 'sub_category', 'method', 'report_days', 'charge_category_id', 'charge_id', 'tax', 'standard_charge', 'amount']);
        $this->testParameters = [];

        if ($id) {
            $this->testId = $id;
            $test = PathologyTest::with(['parameters.unit', 'charge.tax'])->findOrFail($id);
            $this->test_name = $test->test_name;
            $this->short_name = $test->short_name;
            $this->test_type = $test->test_type;
            $this->pathology_category_id = $test->pathology_category_id;
            $this->sub_category = $test->sub_category;
            $this->method = $test->method;
            $this->report_days = $test->report_days;
            $this->charge_id = $test->charge_id;
            $this->charge_category_id = $test->charge->charge_category_id ?? null;

            $this->updatedChargeId($this->charge_id);

            foreach ($test->parameters as $p) {
                $this->testParameters[] = [
                    'parameter_id' => $p->id,
                    'reference_range' => ($p->reference_range_from ?? '') . ' - ' . ($p->reference_range_to ?? ''),
                    'unit' => $p->unit->name ?? ''
                ];
            }
        } else {
            $this->addParameterRow();
        }
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'test_name' => 'required',
            'short_name' => 'required',
            'pathology_category_id' => 'required',
            'charge_id' => 'required',
            'report_days' => 'required|numeric',
            'testParameters.*.parameter_id' => 'required'
        ]);

        $test = PathologyTest::updateOrCreate(['id' => $this->testId], [
            'test_name' => $this->test_name,
            'short_name' => $this->short_name,
            'test_type' => $this->test_type,
            'pathology_category_id' => $this->pathology_category_id,
            'sub_category' => $this->sub_category,
            'method' => $this->method,
            'report_days' => $this->report_days,
            'charge_id' => $this->charge_id,
        ]);

        $parameterIds = collect($this->testParameters)->pluck('parameter_id')->toArray();
        $test->parameters()->sync($parameterIds);

        $this->showModal = false;
        session()->flash('success', 'Test saved successfully.');
    }

    public function delete($id)
    {
        PathologyTest::destroy($id);
        session()->flash('success', 'Test deleted.');
    }

    public function render()
    {
        return view('livewire.admin.pathology.test-index', [
            'tests' => PathologyTest::where('test_name', 'like', '%' . $this->search . '%')
                ->orderBy($this->sortBy, $this->sortDir) // Uses the public properties
                ->paginate($this->perPage),
            'categories' => PathologyCategory::all(),
            'chargeCategories' => ChargeCategory::all(),
            'availableCharges' => Charge::where('charge_category_id', $this->charge_category_id)->get(),
            'allParameters' => PathologyParameter::orderBy('parameter_name')->get()
        ]);
    }
}
