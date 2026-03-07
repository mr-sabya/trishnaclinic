<?php

namespace App\Livewire\Admin\Opd;

use App\Models\OpdAdmission;
use Livewire\Component;
use Livewire\WithPagination;

class OpdIndex extends Component
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
        OpdAdmission::destroy($id);
        session()->flash('success', 'OPD Record deleted.');
    }

    public function render()
    {
        $admissions = OpdAdmission::with(['patient.user', 'doctor', 'charges'])
            ->where('opd_number', 'like', '%' . $this->search . '%')
            ->orWhereHas('patient.user', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.opd.opd-index', [
            'admissions' => $admissions
        ]);
    }
}
