<div>
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary">Doctor Directory</h5>
            <a href="{{ route('admin.doctor.create') }}" wire:navigate class="btn btn-primary btn-sm px-4 shadow-sm">
                <i class="ri-add-line"></i> Add New Doctor
            </a>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Search by name, phone or dept...">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th>Doctor</th>
                            <th>Department / Specialist</th>
                            <th>Fees (Total)</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctors as $doctor)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $doctor->photo ? asset('storage/'.$doctor->photo) : 'https://ui-avatars.com/api/?name='.$doctor->name }}" class="rounded-circle me-3" width="40" height="40">
                                    <div>
                                        <div class="fw-bold">{{ $doctor->name }}</div>
                                        <small class="text-muted">{{ $doctor->phone }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-soft-info text-info border border-info">{{ $doctor->department->name }}</span>
                                <div class="small mt-1">{{ $doctor->specialist->name }}</div>
                            </td>
                            <td>
                                <div class="small">Appt: <strong>{{ number_format($doctor->total_appointment_fee, 2) }}</strong></div>
                                <div class="small">OPD: <strong>{{ number_format($doctor->total_opd_fee, 2) }}</strong></div>
                            </td>
                            <td>
                                <span class="badge {{ $doctor->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.doctor.edit', $doctor->id) }}" wire:navigate class="btn btn-sm btn-outline-primary me-1"><i class="ri-pencil-line"></i></a>
                                <button onclick="confirm('Delete doctor record?') || event.stopImmediatePropagation()" wire:click="delete({{ $doctor->id }})" class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No doctors found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $doctors->links() }}</div>
        </div>
    </div>
</div>