<?php

namespace App\Livewire\Admin\Ipd;

use App\Models\IpdAdmission;
use Livewire\Component;
use Livewire\WithPagination;

class IpdIndex extends Component
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
        IpdAdmission::destroy($id);
        session()->flash('success', 'IPD Record deleted.');
    }

    public function render()
    {
        $admissions = IpdAdmission::with(['patient.user', 'doctor', 'bed.bedGroup'])
            ->where('ipd_number', 'like', '%' . $this->search . '%')
            ->orWhereHas('patient.user', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.ipd.ipd-index', [
            'admissions' => $admissions
        ]);
    }
}
