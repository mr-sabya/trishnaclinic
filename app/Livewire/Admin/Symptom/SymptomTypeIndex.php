<?php

namespace App\Livewire\Admin\Symptom;

use App\Models\SymptomType;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class SymptomTypeIndex extends Component
{
    use WithPagination;

    // Table State
    public $search = '';
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';

    // Form State
    public $showModal = false;
    public $typeId = null;
    public $name;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'typeId']);

        if ($id) {
            $this->typeId = $id;
            $type = SymptomType::findOrFail($id);
            $this->name = $type->name;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('symptom_types')->ignore($this->typeId)],
        ]);

        SymptomType::updateOrCreate(['id' => $this->typeId], [
            'name' => $this->name,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->typeId ? 'Symptom Type updated.' : 'Symptom Type created.');
    }

    public function delete($id)
    {
        try {
            SymptomType::destroy($id);
            session()->flash('success', 'Symptom Type deleted.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete. This type is linked to symptom titles.');
        }
    }

    public function render()
    {
        $types = SymptomType::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.symptom.symptom-type-index', [
            'types' => $types
        ]);
    }
}