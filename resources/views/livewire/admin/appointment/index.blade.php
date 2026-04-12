<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-calendar2-event me-2"></i>Appointment List</h5>
        <a href="{{ route('admin.appointment.create') }}" wire:navigate class="btn btn-primary btn-sm px-4">
            <i class="ri-add-line"></i> Book Appointment
        </a>
    </div>
    <div class="card-body">
        <div class="row mb-3 g-2">
            <div class="col-md-4">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Search Patient, Appt #...">
            </div>
            <div class="col-md-2">
                <select wire:model.live="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach($statuses as $s) <option value="{{ $s->value }}">{{ ucfirst($s->value) }}</option> @endforeach
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle border">
                <thead class="table-light">
                    <tr>
                        <th>Appt No</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date & Time</th>
                        <th>Net Amount</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appt)
                    <tr>
                        <td class="fw-bold">{{ $appt->appointment_number }}</td>
                        <td>
                            <div class="fw-bold">{{ $appt->patient['user']['name'] }}</div>
                            <small class="text-muted">{{ $appt->patient['user']['phone'] }}</small>
                        </td>
                        <td>{{ $appt->doctor->name }}</td>
                        <td>
                            <div>{{ $appt->date->format('d M, Y') }}</div>
                            <small class="badge bg-light text-dark border">{{ $appt->time_slot }}</small>
                        </td>
                        <td class="fw-bold text-primary">৳{{ number_format($appt->net_amount, 2) }}</td>
                        <td>
                            <span class="badge {{ $appt->status->value == 'approved' ? 'bg-success' : ($appt->status->value == 'cancel' ? 'bg-danger' : 'bg-warning') }}">
                                {{ ucfirst($appt->status->value) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.appointment.edit', $appt->id) }}" wire:navigate class="btn btn-sm btn-outline-primary"><i class="ri-pencil-line"></i></a>
                            <button onclick="confirm('Delete Appointment?') || event.stopImmediatePropagation()" wire:click="delete({{ $appt->id }})" class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No appointments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $appointments->links() }}</div>
    </div>
</div>