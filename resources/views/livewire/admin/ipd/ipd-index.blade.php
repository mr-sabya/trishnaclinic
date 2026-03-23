<div>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="bi bi-hospital me-2"></i>IPD Patient List (Inpatient)
            </h5>
            <a href="{{ route('admin.ipd.create') }}" wire:navigate class="btn btn-primary btn-sm px-4 shadow-sm">
                <i class="bi bi-plus-lg"></i> New IPD Admission
            </a>
        </div>

        <div class="card-body">
            <div class="row mb-3 g-3">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control shadow-none" placeholder="Search Patient or IPD No...">
                    </div>
                </div>
                <div class="col-md-1">
                    <select wire:model.live="perPage" class="form-select form-select-sm shadow-none">
                        <option value="10">10</option>
                        <option value="25">25</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th>IPD No / Bed</th>
                            <th>Patient Name</th>
                            <th>Consultant</th>
                            <th>Admission Date</th>
                            <th class="text-end">Total Bill</th>
                            <th class="text-end">Paid</th>
                            <th class="text-end">Balance</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        @forelse($admissions as $ipd)
                        <tr>
                            <td>
                                <a href="{{ route('admin.ipd.show', $ipd->id) }}" wire:navigate class="fw-bold text-decoration-none d-block">
                                    {{ $ipd->ipd_number }}
                                </a>
                                <small class="badge bg-soft-info text-info border">{{ $ipd->bed->name }} ({{ $ipd->bed->bedGroup->name }})</small>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $ipd->patient->user->name }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">MRN: {{ $ipd->patient->mrn_number }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $ipd->doctor->name }}</div>
                            </td>
                            <td>
                                <div>{{ $ipd->admission_date->format('d M, Y') }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $ipd->admission_date->format('h:i A') }}</div>
                            </td>
                            <td class="text-end fw-bold">৳{{ number_format($ipd->grand_total, 2) }}</td>
                            <td class="text-end text-success fw-bold">৳{{ number_format($ipd->total_paid, 2) }}</td>
                            <td class="text-end"><span class="fw-bold {{ $ipd->balance > 0 ? 'text-danger' : 'text-muted' }}">৳{{ number_format($ipd->balance, 2) }}</span></td>
                            <td class="text-center">
                                <span class="badge {{ $ipd->status == 'admitted' ? 'bg-soft-warning text-warning' : 'bg-soft-success text-success' }} border">
                                    {{ ucfirst($ipd->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">Action</button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                        <li><a class="dropdown-item" href="{{ route('admin.ipd.show', $ipd->id) }}" wire:navigate><i class="bi bi-speedometer2 me-2"></i> Case Dashboard</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.ipd.edit', $ipd->id) }}" wire:navigate><i class="bi bi-pencil me-2"></i> Edit Details</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><button class="dropdown-item text-danger" onclick="confirm('Delete this record?') || event.stopImmediatePropagation()" wire:click="delete({{ $ipd->id }})"><i class="bi bi-trash me-2"></i> Delete</button></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">No Inpatient records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $admissions->links() }}</div>
        </div>
    </div>
</div>