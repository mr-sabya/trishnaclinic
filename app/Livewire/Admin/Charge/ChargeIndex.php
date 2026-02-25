<?php

namespace App\Livewire\Admin\Charge;

use App\Models\{Charge, ChargeCategory, ChargeType, TaxCategory, Unit, Tpa, TpaCharge};
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ChargeIndex extends Component
{
    use WithPagination;

    // DataTable State
    public $search = '';
    public $perPage = 10;

    // Form State
    public $showModal = false;
    public $chargeId = null;

    public $charge_type_id, $charge_category_id, $tax_category_id, $unit_id;
    public $name, $code, $standard_charge, $description;

    // TPA Prices State: [tpa_id => amount]
    public $tpa_prices = [];

    protected $paginationTheme = 'bootstrap';

    // Logic: When Charge Type changes, reset Category
    public function updatedChargeTypeId()
    {
        $this->charge_category_id = null;
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['chargeId', 'charge_type_id', 'charge_category_id', 'tax_category_id', 'unit_id', 'name', 'code', 'standard_charge', 'description', 'tpa_prices']);

        if ($id) {
            $this->chargeId = $id;
            $charge = Charge::with('tpaPrices')->findOrFail($id);
            $this->name = $charge->name;
            $this->code = $charge->code;
            $this->standard_charge = $charge->standard_charge;
            $this->description = $charge->description;
            $this->tax_category_id = $charge->tax_category_id;
            $this->unit_id = $charge->unit_id;
            $this->charge_category_id = $charge->charge_category_id;
            $this->charge_type_id = $charge->category->charge_type_id;

            // Load existing TPA prices
            foreach ($charge->tpaPrices as $tp) {
                $this->tpa_prices[$tp->tpa_id] = $tp->scheduled_charge;
            }
        }
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'charge_category_id' => 'required',
            'tax_category_id' => 'required',
            'unit_id' => 'required',
            'name' => 'required|string|max:255',
            'code' => 'required|unique:charges,code,' . $this->chargeId,
            'standard_charge' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () {
            $charge = Charge::updateOrCreate(['id' => $this->chargeId], [
                'charge_category_id' => $this->charge_category_id,
                'tax_category_id' => $this->tax_category_id,
                'unit_id' => $this->unit_id,
                'name' => $this->name,
                'code' => $this->code,
                'standard_charge' => $this->standard_charge,
                'description' => $this->description,
            ]);

            // Sync TPA Prices
            TpaCharge::where('charge_id', $charge->id)->delete();
            foreach ($this->tpa_prices as $tpaId => $amount) {
                if ($amount > 0) {
                    TpaCharge::create([
                        'charge_id' => $charge->id,
                        'tpa_id' => $tpaId,
                        'scheduled_charge' => $amount
                    ]);
                }
            }
        });

        $this->showModal = false;
        session()->flash('success', 'Charge saved successfully.');
    }

    public function render()
    {
        $charges = Charge::with(['category.chargeType', 'tax', 'unit'])
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.charge.charge-index', [
            'charges' => $charges,
            'chargeTypes' => ChargeType::all(),
            'categories' => ChargeCategory::where('charge_type_id', $this->charge_type_id)->get(),
            'taxes' => TaxCategory::all(),
            'units' => Unit::all(),
            'tpas' => Tpa::where('status', true)->get(),
        ]);
    }
}
