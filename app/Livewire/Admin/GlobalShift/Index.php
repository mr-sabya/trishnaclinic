<?php

namespace App\Livewire\Admin\GlobalShift;

use App\Models\GlobalShift;
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
    public $shiftId = null;
    public $name, $start_time, $end_time;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'start_time', 'end_time', 'shiftId']);

        if ($id) {
            $this->shiftId = $id;
            $shift = GlobalShift::findOrFail($id);
            $this->name = $shift->name;
            $this->start_time = $shift->start_time;
            $this->end_time = $shift->end_time;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('global_shifts')->ignore($this->shiftId)],
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ], [
            'end_time.after' => 'The end time must be later than the start time.'
        ]);

        GlobalShift::updateOrCreate(['id' => $this->shiftId], [
            'name' => $this->name,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->shiftId ? 'Shift updated.' : 'Shift created.');
    }

    public function delete($id)
    {
        try {
            GlobalShift::destroy($id);
            session()->flash('success', 'Shift deleted.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete shift. It may be linked to doctor schedules.');
        }
    }

    public function render()
    {
        $shifts = GlobalShift::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.global-shift.index', [
            'shifts' => $shifts
        ]);
    }
}