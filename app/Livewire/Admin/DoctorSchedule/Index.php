<?php

namespace App\Livewire\Admin\DoctorSchedule;

use App\Models\DoctorSchedule;
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

    public function toggleStatus($id)
    {
        $schedule = DoctorSchedule::findOrFail($id);
        $schedule->status = !$schedule->status;
        $schedule->save();
    }

    public function delete($id)
    {
        DoctorSchedule::destroy($id);
        session()->flash('success', 'Schedule deleted successfully.');
    }

    public function render()
    {
        // Eager load doctor.user to get names and doctor.department for context
        $schedules = DoctorSchedule::with(['doctor.user', 'doctor.department', 'shift'])
            ->whereHas('doctor.user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('shift', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.doctor-schedule.index', [
            'schedules' => $schedules
        ]);
    }
}
