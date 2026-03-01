<?php

namespace App\Livewire\Admin\Doctor;

use App\Models\Doctor;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();
        session()->flash('success', 'Doctor record deleted successfully.');
    }

    public function render()
    {
        $doctors = Doctor::with(['department', 'specialist'])
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->orWhereHas('department', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.doctor.index', [
            'doctors' => $doctors
        ]);
    }
}
