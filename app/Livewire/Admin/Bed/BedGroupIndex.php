<?php

namespace App\Livewire\Admin\Bed;

use App\Models\BedGroup;
use App\Models\Floor;
use Livewire\Component;
use Livewire\WithPagination;

class BedGroupIndex extends Component
{
    use WithPagination;

    // DataTable State
    public $search = '';
    public $perPage = 10;
    public $sortBy = 'name';
    public $sortDir = 'ASC';

    // Form State
    public $showModal = false;
    public $groupId = null;
    public $name, $floor_id, $description;

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
        $this->reset(['name', 'floor_id', 'description', 'groupId']);

        if ($id) {
            $this->groupId = $id;
            $group = BedGroup::findOrFail($id);
            $this->name = $group->name;
            $this->floor_id = $group->floor_id;
            $this->description = $group->description;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'floor_id' => 'required|exists:floors,id',
            'description' => 'nullable|string|max:500',
        ]);

        BedGroup::updateOrCreate(['id' => $this->groupId], [
            'name' => $this->name,
            'floor_id' => $this->floor_id,
            'description' => $this->description,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->groupId ? 'Bed Group updated.' : 'Bed Group created.');
    }

    public function delete($id)
    {
        try {
            $group = BedGroup::findOrFail($id);

            // Check if linked to individual Beds
            if ($group->beds()->count() > 0) {
                session()->flash('error', 'Cannot delete. This group has active beds assigned to it.');
                return;
            }

            $group->delete();
            session()->flash('success', 'Bed Group deleted.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error occurred during deletion.');
        }
    }

    public function render()
    {
        $groups = BedGroup::with('floor')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.bed.bed-group-index', [
            'groups' => $groups,
            'floors' => Floor::orderBy('name')->get()
        ]);
    }
}
