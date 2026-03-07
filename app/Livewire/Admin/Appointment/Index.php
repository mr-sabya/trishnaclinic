<?php

namespace App\Livewire\Admin\Appointment;

use App\Models\Appointment;
use App\Enums\AppointmentStatus;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = ''; // Filter by Enum status
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        Appointment::destroy($id);
        session()->flash('success', 'Appointment deleted.');
    }

    public function render()
    {
        $appointments = Appointment::with(['patient', 'doctor', 'paymentMethod'])
            ->when($this->search, function ($q) {
                $q->where('appointment_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('patient', fn($p) => $p->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('doctor', fn($d) => $d->where('name', 'like', '%' . $this->search . '%'));
            })
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.appointment.index', [
            'appointments' => $appointments,
            'statuses' => AppointmentStatus::cases()
        ]);
    }
}
