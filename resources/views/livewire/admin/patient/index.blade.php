<div class="">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-people me-2 text-primary"></i>Patient Directory</h5>
                    <a href="{{ route('admin.patient.create') }}" wire:navigate class="btn btn-primary btn-sm">
                        <i class="bi bi-person-plus me-1"></i> Add New Patient
                    </a>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4 g-3">
                        <div class="col-md-1">
                            <select wire:model.live="perPage" class="form-select">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="col-md-7"></div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                                <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0" placeholder="Search MRN, Name or Phone...">
                            </div>
                        </div>
                    </div>

                    <!-- DataTable -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border-top">
                            <thead class="table-light">
                                <tr>
                                    <th style="cursor:pointer" wire:click="setSortBy('mrn_number')">
                                        MRN {!! $sortBy == 'mrn_number' ? ($sortDir == 'ASC' ? '↑' : '↓') : '' !!}
                                    </th>
                                    <th>Patient Information</th>
                                    <th>Age/Gender</th>
                                    <th>TPA (Insurance)</th>
                                    <th style="cursor:pointer" wire:click="setSortBy('created_at')">
                                        Reg. Date {!! $sortBy == 'created_at' ? ($sortDir == 'ASC' ? '↑' : '↓') : '' !!}
                                    </th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patients as $patient)
                                <tr>
                                    <td><span class="fw-bold text-primary">{{ $patient->mrn_number }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($patient->photo)
                                            <img src="{{ asset('storage/' . $patient->photo) }}" class="rounded-circle me-2" width="35" height="35" alt="">
                                            @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" width="35" height="35">
                                                <i class="bi bi-person text-muted"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $patient->user->name }}</div>
                                                <small class="text-muted"><i class="bi bi-telephone"></i> {{ $patient->user->phone }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small>{{ $patient->gender->value }}</small>
                                        <div class="text-muted" style="font-size: 0.8rem;">
                                            {{ $patient->detailed_age['y'] }}Y {{ $patient->detailed_age['m'] }}M {{ $patient->detailed_age['d'] }}D
                                        </div>
                                    </td>
                                    <td>
                                        @if($patient->tpa)
                                        <span class="badge bg-soft-info text-info">{{ $patient->tpa->name }}</span>
                                        <div class="small text-muted">{{ $patient->insurance_id }}</div>
                                        @else
                                        <span class="text-muted small">Self Pay</span>
                                        @endif
                                    </td>
                                    <td>{{ $patient->created_at->format('d M, Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.patients.edit', $patient->id) }}" wire:navigate class="btn btn-sm btn-outline-primary shadow-sm me-1">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button onclick="confirm('Permanently delete this patient and their login account?') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $patient->id }})" class="btn btn-sm btn-outline-danger shadow-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No patient records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $patients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>