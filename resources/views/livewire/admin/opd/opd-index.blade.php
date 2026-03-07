<div>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="bi bi-person-badge-fill me-2"></i>OPD Patient List
            </h5>
            <a href="{{ route('admin.opd.create') }}" wire:navigate class="btn btn-primary btn-sm px-4 shadow-sm">
                <i class="ri-add-line"></i> New OPD Admission
            </a>
        </div>

        <div class="card-body">
            <!-- Filters & Search -->
            <div class="row mb-3 g-3">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control shadow-none" placeholder="Search by Name, OPD No or MRN...">
                    </div>
                </div>
                <div class="col-md-1">
                    <select wire:model.live="perPage" class="form-select form-select-sm shadow-none">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>

            <!-- OPD Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th>OPD No</th>
                            <th>Patient Name</th>
                            <th>Consultant</th>
                            <th>Date</th>
                            <th class="text-end">Total Bill</th>
                            <th class="text-end">Paid</th>
                            <th class="text-end">Balance</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        @forelse($admissions as $opd)
                        <tr>
                            <td class="fw-bold text-primary">
                                <a href="{{ route('admin.opd.show', $opd->id) }}" wire:navigate class="text-decoration-none">
                                    {{ $opd->opd_number }}
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $opd->patient->user->name }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">MRN: {{ $opd->patient->mrn_number }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $opd->doctor->name }}</div>
                            </td>
                            <td>
                                <div>{{ $opd->appointment_date->format('d M, Y') }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $opd->appointment_date->format('h:i A') }}</div>
                            </td>
                            <td class="text-end fw-bold text-dark">
                                ৳{{ number_format($opd->grand_total, 2) }}
                            </td>
                            <td class="text-end text-success fw-bold">
                                ৳{{ number_format($opd->total_paid, 2) }}
                            </td>
                            <td class="text-end">
                                <span class="fw-bold {{ $opd->balance > 0 ? 'text-danger' : 'text-muted' }}">
                                    ৳{{ number_format($opd->balance, 2) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $opd->status == 'ongoing' ? 'bg-soft-warning text-warning' : 'bg-soft-success text-success' }} border">
                                    {{ ucfirst($opd->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border shadow-none dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                        <li><a class="dropdown-item" href="{{ route('admin.opd.show', $opd->id) }}" wire:navigate><i class="bi bi-eye me-2"></i> Patient Dashboard</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.opd.edit', $opd->id) }}" wire:navigate><i class="bi bi-pencil me-2"></i> Edit Admission</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><button class="dropdown-item text-danger" onclick="confirm('Delete this OPD record?') || event.stopImmediatePropagation()" wire:click="delete({{ $opd->id }})"><i class="bi bi-trash me-2"></i> Delete</button></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-folder-x fs-1 d-block mb-2"></i>
                                No OPD records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $admissions->links() }}
            </div>
        </div>
    </div>
</div>