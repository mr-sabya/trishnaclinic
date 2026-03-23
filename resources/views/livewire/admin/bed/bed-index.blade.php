<div>
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-hospital me-2"></i>Bed Management</h5>
            <button wire:click="openModal" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="bi bi-plus-lg"></i> Add Bed
            </button>
        </div>

        <div class="card-body">
            <!-- Table Controls -->
            <div class="row mb-3 align-items-center">
                <div class="col-md-1">
                    <select wire:model.live="perPage" class="form-select form-select-sm">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="col-md-8 text-center">
                    @if (session()->has('success')) <span class="badge bg-success py-2 px-3 shadow-sm"><i class="bi bi-check-circle me-1"></i> {{ session('success') }}</span> @endif
                    @if (session()->has('error')) <span class="badge bg-danger py-2 px-3 shadow-sm"><i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}</span> @endif
                </div>
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Search beds...">
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th style="cursor:pointer" wire:click="setSortBy('name')">
                                Bed Name {!! $sortBy == 'name' ? ($sortDir == 'ASC' ? '↑' : '↓') : '' !!}
                            </th>
                            <th>Bed Group (Floor)</th>
                            <th>Bed Type</th>
                            <th class="text-center">Availability</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($beds as $bed)
                        <tr>
                            <td class="fw-bold text-dark">{{ $bed->name }}</td>
                            <td>
                                <span class="text-dark">{{ $bed->bedGroup->name ?? 'N/A' }}</span>
                                <div class="text-muted small">{{ $bed->bedGroup->floor->name ?? '' }}</div>
                            </td>
                            <td><span class="badge bg-light text-primary border">{{ $bed->bedType->name ?? 'N/A' }}</span></td>
                            <td class="text-center">
                                @if($bed->isOccupied())
                                <span class="badge bg-danger shadow-sm"><i class="bi bi-person-fill me-1"></i> Occupied</span>
                                @else
                                <span class="badge bg-success shadow-sm"><i class="bi bi-check-lg me-1"></i> Available</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($bed->is_active)
                                <span class="text-success"><i class="bi bi-circle-fill small me-1"></i> Active</span>
                                @else
                                <span class="text-muted small">Maintenance</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button wire:click="openModal({{ $bed->id }})" class="btn btn-sm btn-outline-primary shadow-sm me-1" @if($bed->isOccupied()) title="Edit restricted for occupied beds" @endif>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button onclick="confirm('Delete this bed?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $bed->id }})" class="btn btn-sm btn-outline-danger shadow-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No beds found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $beds->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary p-3">
                    <h5 class="modal-title fw-bold text-white">{{ $bedId ? 'Edit Bed' : 'Add New Bed' }}</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Bed Name/Number <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Bed-101, ICU-01">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Bed Group (Ward) <span class="text-danger">*</span></label>
                                <select wire:model="bed_group_id" class="form-select @error('bed_group_id') is-invalid @enderror">
                                    <option value="">Select Ward</option>
                                    @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }} ({{ $group->floor->name ?? '' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Bed Type <span class="text-danger">*</span></label>
                                <select wire:model="bed_type_id" class="form-select @error('bed_type_id') is-invalid @enderror">
                                    <option value="">Select Type</option>
                                    @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" wire:model="is_active" id="bedActiveStatus">
                            <label class="form-check-label fw-bold" for="bedActiveStatus">Bed is currently functional/active</label>
                        </div>

                        @if($bedId && Bed::find($bedId)->isOccupied())
                        <div class="alert alert-warning mt-3 mb-0 py-2 border-0 small">
                            <i class="bi bi-info-circle me-1"></i> This bed is currently occupied. Changing details is not recommended until the patient is discharged.
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary px-4 shadow-sm" wire:click="$set('showModal', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            {{ $bedId ? 'Update Bed' : 'Save Bed' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>