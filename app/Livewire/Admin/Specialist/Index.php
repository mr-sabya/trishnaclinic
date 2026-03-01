<?php

namespace App\Livewire\Admin\Specialist;

use App\Models\Specialist;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    // Table State
    public $search = '';
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';

    // Form State
    public $showModal = false;
    public $specialistId = null;
    public $name;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'specialistId']);

        if ($id) {
            $this->specialistId = $id;
            $specialist = Specialist::findOrFail($id);
            $this->name = $specialist->name;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('specialists')->ignore($this->specialistId)],
        ]);

        Specialist::updateOrCreate(['id' => $this->specialistId], [
            'name' => $this->name,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->specialistId ? 'Specialist updated.' : 'Specialist created.');
    }

    public function delete($id)
    {
        try {
            Specialist::destroy($id);
            session()->flash('success', 'Specialist deleted.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete. This specialist may be linked to a doctor.');
        }
    }

    public function render()
    {
        $specialists = Specialist::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.specialist.index', [
            'specialists' => $specialists
        ]);
    }
}