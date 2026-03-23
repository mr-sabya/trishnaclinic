<?php

namespace App\Livewire\Admin\Radiology;

use App\Models\RadiologyUnit;
use Livewire\Component;
use Livewire\WithPagination;

class UnitIndex extends Component
{
    use WithPagination;

    // DataTable State
    public $search = '';
    public $perPage = 10;
    public $sortBy = 'name';
    public $sortDir = 'ASC';

    // Form State
    public $showModal = false;
    public $unitId = null;
    public $name;

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
        $this->reset(['name', 'unitId']);

        if ($id) {
            $this->unitId = $id;
            $unit = RadiologyUnit::findOrFail($id);
            $this->name = $unit->name;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:radiology_units,name,' . $this->unitId,
        ]);

        RadiologyUnit::updateOrCreate(['id' => $this->unitId], [
            'name' => $this->name,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->unitId ? 'Radiology unit updated successfully.' : 'Radiology unit created successfully.');
    }

    public function delete($id)
    {
        try {
            RadiologyUnit::destroy($id);
            session()->flash('success', 'Radiology unit deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete Unit. It might be linked to existing radiology parameters.');
        }
    }

    public function render()
    {
        $units = RadiologyUnit::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.radiology.unit-index', [
            'units' => $units
        ]);
    }
}
