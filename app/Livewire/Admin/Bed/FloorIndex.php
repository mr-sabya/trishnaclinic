<?php

namespace App\Livewire\Admin\Bed;

use App\Models\Floor;
use Livewire\Component;
use Livewire\WithPagination;

class FloorIndex extends Component
{
    use WithPagination;

    // DataTable State
    public $search = '';
    public $perPage = 10;
    public $sortBy = 'name';
    public $sortDir = 'ASC';

    // Form State
    public $showModal = false;
    public $floorId = null;
    public $name, $description;

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
        $this->reset(['name', 'description', 'floorId']);

        if ($id) {
            $this->floorId = $id;
            $floor = Floor::findOrFail($id);
            $this->name = $floor->name;
            $this->description = $floor->description;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:floors,name,' . $this->floorId,
            'description' => 'nullable|string|max:500',
        ]);

        Floor::updateOrCreate(['id' => $this->floorId], [
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->floorId ? 'Floor updated successfully.' : 'Floor created successfully.');
    }

    public function delete($id)
    {
        try {
            $floor = Floor::findOrFail($id);

            // Check if linked to Bed Groups (Safety Check)
            if ($floor->bedGroups()->count() > 0) {
                session()->flash('error', 'Cannot delete. This floor has active bed groups (wards) assigned to it.');
                return;
            }

            $floor->delete();
            session()->flash('success', 'Floor deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting.');
        }
    }

    public function render()
    {
        $floors = Floor::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.bed.floor-index', [
            'floors' => $floors
        ]);
    }
}
