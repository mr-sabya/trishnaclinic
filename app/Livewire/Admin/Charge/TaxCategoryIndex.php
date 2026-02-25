<?php

namespace App\Livewire\Admin\Charge;

use App\Models\TaxCategory;
use Livewire\Component;
use Livewire\WithPagination;

class TaxCategoryIndex extends Component
{
    use WithPagination;

    // DataTable State
    public $search = '';
    public $perPage = 10;
    public $sortBy = 'name';
    public $sortDir = 'ASC';

    // Form State
    public $showModal = false;
    public $taxId = null;
    public $name, $percentage;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setSortBy($field)
    {
        $this->sortDir = ($this->sortBy === $field && $this->sortDir === 'ASC') ? 'DESC' : 'ASC';
        $this->sortBy = $field;
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'percentage', 'taxId']);

        if ($id) {
            $this->taxId = $id;
            $tax = TaxCategory::findOrFail($id);
            $this->name = $tax->name;
            $this->percentage = $tax->percentage;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        TaxCategory::updateOrCreate(['id' => $this->taxId], [
            'name' => $this->name,
            'percentage' => $this->percentage,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->taxId ? 'Tax updated successfully.' : 'Tax created successfully.');
    }

    public function delete($id)
    {
        try {
            TaxCategory::destroy($id);
            session()->flash('success', 'Tax category deleted.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete tax category. It is likely linked to active charges.');
        }
    }

    public function render()
    {
        $taxes = TaxCategory::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.charge.tax-category-index', [
            'taxes' => $taxes
        ]);
    }
}
