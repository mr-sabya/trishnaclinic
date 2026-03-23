<?php

namespace App\Livewire\Admin\Bed;

use App\Models\BedType;
use Livewire\Component;
use Livewire\WithPagination;

class BedTypeIndex extends Component
{
    use WithPagination;

    // DataTable State
    public $search = '';
    public $perPage = 10;
    public $sortBy = 'name';
    public $sortDir = 'ASC';

    // Form State
    public $showModal = false;
    public $bedTypeId = null;
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
        $this->reset(['name', 'bedTypeId']);

        if ($id) {
            $this->bedTypeId = $id;
            $type = BedType::findOrFail($id);
            $this->name = $type->name;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:bed_types,name,' . $this->bedTypeId,
        ]);

        BedType::updateOrCreate(['id' => $this->bedTypeId], [
            'name' => $this->name,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->bedTypeId ? 'Bed Type updated successfully.' : 'Bed Type created successfully.');
    }

    public function delete($id)
    {
        try {
            $type = BedType::findOrFail($id);

            // Check if linked to Beds (Safety Check)
            if ($type->beds()->count() > 0) {
                session()->flash('error', 'Cannot delete. This type is assigned to active beds.');
                return;
            }

            $type->delete();
            session()->flash('success', 'Bed Type deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting.');
        }
    }

    public function render()
    {
        $types = BedType::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.bed.bed-type-index', [
            'types' => $types
        ]);
    }
}
