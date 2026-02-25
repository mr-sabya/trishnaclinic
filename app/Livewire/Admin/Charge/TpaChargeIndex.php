<?php

namespace App\Livewire\Admin\Charge;

use App\Models\{Charge, ChargeCategory, Tpa, TpaCharge};
use Livewire\Component;
use Livewire\WithPagination;

class TpaChargeIndex extends Component
{
    use WithPagination;

    // Filter State
    public $selectedTpa = '';
    public $selectedCategory = '';
    public $search = '';

    // Editing State: [charge_id => price]
    public $prices = [];

    protected $paginationTheme = 'bootstrap';

    /**
     * When TPA or Category changes, reload the price array
     */
    public function updatedSelectedTpa()
    {
        $this->loadPrices();
    }
    public function updatedSelectedCategory()
    {
        $this->loadPrices();
    }

    public function loadPrices()
    {
        $this->reset(['prices']);

        if (!$this->selectedTpa) return;

        $tpaCharges = TpaCharge::where('tpa_id', $this->selectedTpa)->get();
        foreach ($tpaCharges as $tc) {
            $this->prices[$tc->charge_id] = $tc->scheduled_charge;
        }
    }

    public function savePrices()
    {
        if (!$this->selectedTpa) {
            session()->flash('error', 'Please select a TPA first.');
            return;
        }

        foreach ($this->prices as $chargeId => $amount) {
            if ($amount === '' || $amount === null) {
                // If price is cleared, remove the override
                TpaCharge::where('tpa_id', $this->selectedTpa)
                    ->where('charge_id', $chargeId)
                    ->delete();
                continue;
            }

            TpaCharge::updateOrCreate(
                ['tpa_id' => $this->selectedTpa, 'charge_id' => $chargeId],
                ['scheduled_charge' => $amount]
            );
        }

        session()->flash('success', 'TPA Price List updated successfully.');
    }

    public function render()
    {
        $charges = [];

        if ($this->selectedTpa) {
            $charges = Charge::with(['category', 'unit'])
                ->when($this->selectedCategory, fn($q) => $q->where('charge_category_id', $this->selectedCategory))
                ->where('name', 'like', '%' . $this->search . '%')
                ->paginate(20);
        }

        return view('livewire.admin.charge.tpa-charge-index', [
            'charges' => $charges,
            'tpas' => Tpa::where('status', true)->get(),
            'categories' => ChargeCategory::all()
        ]);
    }
}
