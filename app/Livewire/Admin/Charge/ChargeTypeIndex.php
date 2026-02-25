<?php

namespace App\Livewire\Admin\Charge;

use App\Models\ChargeType;
use App\Enums\Module;
use Livewire\Component;
use Livewire\WithPagination;

class ChargeTypeIndex extends Component
{
    use WithPagination;

    // DataTable State
    public $search = '';
    public $perPage = 10;
    public $sortBy = 'name';
    public $sortDir = 'ASC';

    // Form State
    public $showModal = false;
    public $typeId = null;
    public $name;
    public $selectedModules = []; // Array to store checkbox values
    public $status = true;

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
        $this->reset(['name', 'selectedModules', 'status', 'typeId']);

        if ($id) {
            $this->typeId = $id;
            $type = ChargeType::findOrFail($id);
            $this->name = $type->name;
            $this->selectedModules = $type->modules; // Model casts JSON to array
            $this->status = $type->status;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'selectedModules' => 'required|array|min:1',
        ], [
            'selectedModules.required' => 'Please select at least one module.'
        ]);

        ChargeType::updateOrCreate(['id' => $this->typeId], [
            'name' => $this->name,
            'modules' => $this->selectedModules,
            'status' => $this->status,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->typeId ? 'Charge Type updated.' : 'Charge Type created.');
    }

    public function delete($id)
    {
        try {
            ChargeType::destroy($id);
            session()->flash('success', 'Charge Type deleted.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete. Category might be linked to charges.');
        }
    }

    public function render()
    {
        $chargeTypes = ChargeType::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.charge.charge-type-index', [
            'chargeTypes' => $chargeTypes,
            'modules' => Module::cases() // Pass Enum cases to UI
        ]);
    }
}
