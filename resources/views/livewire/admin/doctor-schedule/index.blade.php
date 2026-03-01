<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-calendar-check me-2"></i>Doctor Schedules</h5>
        <a href="{{ route('admin.doctor-schedules.create') }}" wire:navigate class="btn btn-primary btn-sm px-3">
            <i class="ri-add-line"></i> Add Schedule
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle border">
                <thead class="table-light">
                    <tr>
                        <th>Doctor</th>
                        <th>Shift</th>
                        <th>Days</th>
                        <th>Time</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $item)
                    <tr>
                        <td class="fw-bold">{{ $item->doctor->name }}</td>
                        <td><span class="badge bg-soft-info text-info">{{ $item->shift->name }}</span></td>
                        <td>
                            @foreach($item->available_days as $day)
                            <span class="badge bg-light text-dark border me-1 small">{{ $day->value }}</span>
                            @endforeach
                        </td>
                        <td>{{ date('h:i A', strtotime($item->start_time)) }} - {{ date('h:i A', strtotime($item->end_time)) }}</td>
                        <td class="small">{{ $item->max_appointments }} Slots ({{ $item->avg_consultation_time }}m ea)</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:click="toggleStatus({{ $item->id }})" {{ $item->status ? 'checked' : '' }}>
                            </div>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.appointment.doctor-schedules.manage', $item->id) }}" wire:navigate class="btn btn-sm btn-outline-primary"><i class="ri-pencil-line"></i></a>
                            <button onclick="confirm('Delete schedule?') || event.stopImmediatePropagation()" wire:click="delete({{ $item->id }})" class="btn btn-sm btn-outline-danger ms-1"><i class="ri-delete-bin-line"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No schedules found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $schedules->links() }}</div>
    </div>
</div>