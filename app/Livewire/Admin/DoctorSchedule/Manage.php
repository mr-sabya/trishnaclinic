<?php

namespace App\Livewire\Admin\DoctorSchedule;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\GlobalShift;
use App\Enums\DayOfWeek;
use Livewire\Component;

class Manage extends Component
{
    public $scheduleId;

    // Form fields
    public $doctor_id, $global_shift_id, $start_time, $end_time;
    public $avg_consultation_time = 15;
    public $max_appointments = 20;
    public $available_days = []; // Will store strings like ["Monday", "Tuesday"]
    public $status = true;

    public function mount($id = null)
    {
        if ($id) {
            $this->scheduleId = $id;
            $schedule = DoctorSchedule::findOrFail($id);
            $this->fill($schedule->toArray());
            // Ensure available_days is an array of strings for the checkboxes
            $this->available_days = collect($schedule->available_days)->map(fn($day) => $day->value)->toArray();
        }
    }

    public function save()
    {
        $this->validate([
            'doctor_id' => 'required',
            'global_shift_id' => 'required',
            'available_days' => 'required|array|min:1',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'avg_consultation_time' => 'required|numeric|min:1',
        ]);

        DoctorSchedule::updateOrCreate(['id' => $this->scheduleId], [
            'doctor_id' => $this->doctor_id,
            'global_shift_id' => $this->global_shift_id,
            'available_days' => $this->available_days,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'avg_consultation_time' => $this->avg_consultation_time,
            'max_appointments' => $this->max_appointments,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Doctor schedule saved successfully.');
        return redirect()->route('admin.doctor-schedules.index');
    }

    public function render()
    {
        return view('livewire.admin.doctor-schedule.manage', [
            'doctors' => Doctor::where('is_active', true)->get(),
            'shifts' => GlobalShift::all(),
            'days' => DayOfWeek::cases()
        ]);
    }
}