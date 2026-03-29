<?php

namespace App\Livewire\Admin\Doctor;

use App\Models\Doctor;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

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
        DB::transaction(function () use ($id) {
            $doctor = Doctor::findOrFail($id);
            $user = $doctor->user;

            // Delete doctor record
            $doctor->delete();

            // Optionally delete the user account as well
            if ($user) {
                $user->delete();
            }
        });

        session()->flash('success', 'Doctor and associated user account deleted successfully.');
    }

    public function render()
    {
        $doctors = Doctor::with(['user', 'department', 'specialist'])
            ->where(function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                })
                    ->orWhereHas('department', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('designation', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.doctor.index', [
            'doctors' => $doctors
        ]);
    }
}
