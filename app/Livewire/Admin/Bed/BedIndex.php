<?php

namespace App\Livewire\Admin\Bed;

use App\Models\Bed;
use App\Models\BedType;
use App\Models\BedGroup;
use Livewire\Component;
use Livewire\WithPagination;

class BedIndex extends Component
{
    use WithPagination;

    // DataTable State
    public $search = '';
    public $perPage = 10;
    public $sortBy = 'name';
    public $sortDir = 'ASC';

    // Form State
    public $showModal = false;
    public $bedId = null;
    public $name, $bed_type_id, $bed_group_id, $is_active = true;

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
        $this->reset(['name', 'bed_type_id', 'bed_group_id', 'is_active', 'bedId']);

        if ($id) {
            $this->bedId = $id;
            $bed = Bed::findOrFail($id);
            $this->name = $bed->name;
            $this->bed_type_id = $bed->bed_type_id;
            $this->bed_group_id = $bed->bed_group_id;
            $this->is_active = $bed->is_active;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'bed_type_id' => 'required|exists:bed_types,id',
            'bed_group_id' => 'required|exists:bed_groups,id',
            'is_active' => 'boolean'
        ]);

        Bed::updateOrCreate(['id' => $this->bedId], [
            'name' => $this->name,
            'bed_type_id' => $this->bed_type_id,
            'bed_group_id' => $this->bed_group_id,
            'is_active' => $this->is_active,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->bedId ? 'Bed updated.' : 'Bed created.');
    }

    public function delete($id)
    {
        try {
            $bed = Bed::findOrFail($id);
            if ($bed->isOccupied()) {
                session()->flash('error', 'Cannot delete an occupied bed. Discharge the patient first.');
                return;
            }
            $bed->delete();
            session()->flash('success', 'Bed deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error occurred during deletion.');
        }
    }

    public function render()
    {
        $beds = Bed::with(['bedType', 'bedGroup.floor'])
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.bed.bed-index', [
            'beds' => $beds,
            'types' => BedType::orderBy('name')->get(),
            'groups' => BedGroup::with('floor')->orderBy('name')->get()
        ]);
    }
}
