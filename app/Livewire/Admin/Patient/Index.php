<?php

namespace App\Livewire\Admin\Patient;

use App\Models\Patient;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'created_at';
    public $sortDir = 'DESC';

    protected $paginationTheme = 'bootstrap';

    public function setSortBy($field)
    {
        $this->sortDir = ($this->sortBy === $field && $this->sortDir === 'ASC') ? 'DESC' : 'ASC';
        $this->sortBy = $field;
    }

    public function delete($id)
    {
        $patient = Patient::findOrFail($id);
        // Delete associated user account as well
        if ($patient->user) {
            $patient->user->delete();
        }
        $patient->delete();
        session()->flash('success', 'Patient record deleted.');
    }

    public function render()
    {
        $patients = Patient::with(['user', 'tpa'])
            ->where('mrn_number', 'like', "%{$this->search}%")
            ->orWhereHas('user', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.patient.index', compact('patients'));
    }
}
