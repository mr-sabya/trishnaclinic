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
        $schedules = DoctorSchedule::with(['doctor', 'shift'])
            ->whereHas('doctor', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.doctor-schedule.index', [
            'schedules' => $schedules
        ]);
    }
}