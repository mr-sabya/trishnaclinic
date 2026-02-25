<?php

namespace App\Livewire\Admin\Charge;

use App\Models\Unit;
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
    public $name, $short_name;

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
        $this->reset(['name', 'short_name', 'unitId']);

        if ($id) {
            $this->unitId = $id;
            $unit = Unit::findOrFail($id);
            $this->name = $unit->name;
            $this->short_name = $unit->short_name;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
        ]);

        Unit::updateOrCreate(['id' => $this->unitId], [
            'name' => $this->name,
            'short_name' => $this->short_name,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->unitId ? 'Unit updated successfully.' : 'Unit created successfully.');
    }

    public function delete($id)
    {
        try {
            Unit::destroy($id);
            session()->flash('success', 'Unit deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete Unit. It might be linked to existing charges.');
        }
    }

    public function render()
    {
        $units = Unit::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('short_name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.charge.unit-index', [
            'units' => $units
        ]);
    }
}
