<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-primary"><i class="ri-calendar-check-line me-2"></i>Doctor Duty Schedules</h5>
        <a href="{{ route('admin.doctor-schedules.create') }}" wire:navigate class="btn btn-primary btn-sm px-3 shadow-sm">
            <i class="ri-add-line"></i> Add Schedule
        </a>
    </div>
    <div class="card-body">
        <!-- Search Bar -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="ri-search-line text-muted"></i></span>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 shadow-none" placeholder="Search doctor or shift...">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle border">
                <thead class="table-light">
                    <tr>
                        <th>Doctor / Dept</th>
                        <th>Classification</th>
                        <th>Shift</th>
                        <th>Available Days</th>
                        <th>Time Window</th>
                        <th>Load / Slots</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $item)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->doctor->user->name }}</div>
                            <div class="small text-muted">{{ $item->doctor->department->name ?? 'N/A' }}</div>
                        </td>
                        <td>
                            @if($item->doctor->type === 'permanent')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2">Permanent</span>
                            @else
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2">On-Call</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2">
                                <i class="ri-time-line me-1"></i>{{ $item->shift->name }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($item->available_days as $day)
                                <span class="x-small-badge bg-light border text-dark">{{ substr($day->value, 0, 3) }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="small fw-bold">{{ date('h:i A', strtotime($item->start_time)) }}</div>
                            <div class="small text-muted">{{ date('h:i A', strtotime($item->end_time)) }}</div>
                        </td>
                        <td>
                            <div class="small"><strong>{{ $item->max_appointments }}</strong> Slots</div>
                            <div class="x-small text-muted">{{ $item->avg_consultation_time }} mins / patient</div>
                        </td>
                        <td class="text-center">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input" type="checkbox" role="switch" wire:click="toggleStatus({{ $item->id }})" {{ $item->status ? 'checked' : '' }}>
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm shadow-sm">
                                <a href="{{ route('admin.doctor-schedules.edit', $item->id) }}" wire:navigate class="btn btn-outline-primary">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <button onclick="confirm('Are you sure you want to delete this duty schedule?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $item->id }})"
                                    class="btn btn-outline-danger">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="ri-calendar-event-line fs-1 text-muted d-block mb-2"></i>
                            <span class="text-muted">No duty schedules found.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 d-flex justify-content-center">
            {{ $schedules->links() }}
        </div>
    </div>

    <style>
        .x-small-badge {
            font-size: 0.65rem;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .x-small {
            font-size: 0.75rem;
        }
    </style>
</div>