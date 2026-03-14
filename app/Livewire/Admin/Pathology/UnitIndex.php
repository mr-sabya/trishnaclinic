<?php

namespace App\Livewire\Admin\Pathology;

use App\Models\PathologyUnit;
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
            $unit = PathologyUnit::findOrFail($id);
            $this->name = $unit->name;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:pathology_units,name,' . $this->unitId,
        ]);

        PathologyUnit::updateOrCreate(['id' => $this->unitId], [
            'name' => $this->name,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->unitId ? 'Pathology unit updated successfully.' : 'Pathology unit created successfully.');
    }

    public function delete($id)
    {
        try {
            PathologyUnit::destroy($id);
            session()->flash('success', 'Pathology unit deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete Unit. It might be linked to existing pathology parameters.');
        }
    }

    public function render()
    {
        $units = PathologyUnit::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.pathology.unit-index', [
            'units' => $units
        ]);
    }
}