<div>
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h5 class="mb-0 fw-bold text-primary"><i class="ri-nurse-line me-1"></i> Doctor Directory</h5>
            <a href="{{ route('admin.doctor.create') }}" wire:navigate class="btn btn-primary btn-sm px-4 shadow-sm">
                <i class="ri-add-line"></i> Add New Doctor
            </a>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="ri-search-line text-muted"></i></span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 shadow-none" placeholder="Search name, phone, dept...">
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th>Doctor Details</th>
                            <th>Classification</th>
                            <th>Dept / Specialist</th>
                            <th>Fees (Dr / Hosp)</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctors as $doctor)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="position-relative">
                                        <img src="{{ $doctor->photo ? asset('storage/'.$doctor->photo) : 'https://ui-avatars.com/api/?background=random&name='.urlencode($doctor->user->name) }}"
                                            class="rounded shadow-sm me-3 border" width="45" height="45" style="object-fit: cover;">
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $doctor->user->name }}</div>
                                        <div class="small text-muted"><i class="ri-phone-line me-1"></i>{{ $doctor->user->phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($doctor->type === 'permanent')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2">
                                    <i class="ri-shield-user-line me-1"></i>Permanent
                                </span>
                                @else
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2">
                                    <i class="ri-external-link-line me-1"></i>On-Call
                                </span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold small text-primary">{{ $doctor->department->name }}</div>
                                <div class="small text-muted">{{ $doctor->specialist->name }}</div>
                                <div class="x-small text-muted italic">{{ $doctor->designation }}</div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <div class="x-small d-flex justify-content-between">
                                        <span class="text-muted">Appt:</span>
                                        <span class="fw-bold">{{ number_format($doctor->appointment_doctor_fee) }} / {{ number_format($doctor->appointment_hospital_fee) }}</span>
                                    </div>
                                    <div class="x-small d-flex justify-content-between">
                                        <span class="text-muted">OPD:</span>
                                        <span class="fw-bold">{{ number_format($doctor->opd_doctor_fee) }} / {{ number_format($doctor->opd_hospital_fee) }}</span>
                                    </div>
                                    <div class="x-small d-flex justify-content-between">
                                        <span class="text-muted">IPD:</span>
                                        <span class="fw-bold text-danger">{{ number_format($doctor->ipd_doctor_fee) }} / {{ number_format($doctor->ipd_hospital_fee) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" disabled {{ $doctor->is_active ? 'checked' : '' }}>
                                    <label class="x-small {{ $doctor->is_active ? 'text-success' : 'text-danger' }}">
                                        {{ $doctor->is_active ? 'Active' : 'Offline' }}
                                    </label>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="btn-group shadow-sm">
                                    <a href="{{ route('admin.doctor.edit', $doctor->id) }}" wire:navigate class="btn btn-sm btn-outline-primary" title="Edit Profile">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <button onclick="confirm('Delete doctor record and associated user account?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $doctor->id }})"
                                        class="btn btn-sm btn-outline-danger" title="Delete Doctor">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="ri-user-search-line fs-1 text-muted d-block mb-2"></i>
                                <span class="text-muted">No doctors found matching your criteria.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3 d-flex justify-content-center">
                {{ $doctors->links() }}
            </div>
        </div>
    </div>

    <style>
        .x-small {
            font-size: 0.75rem;
        }

        .italic {
            font-style: italic;
        }
    </style>
</div>