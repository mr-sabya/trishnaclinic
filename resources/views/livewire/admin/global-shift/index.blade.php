<div>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-clock-history me-2"></i>Global Shifts</h5>
                    <button wire:click="openModal" class="btn btn-primary btn-sm px-3">
                        <i class="ri-add-line"></i> Add Shift
                    </button>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-1">
                            <select wire:model.live="perPage" class="form-select form-select-sm">
                                <option value="10">10</option>
                                <option value="25">25</option>
                            </select>
                        </div>
                        <div class="col-md-8"></div>
                        <div class="col-md-3">
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Search shifts...">
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="table-light">
                                <tr>
                                    <th>Shift Name</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Duration</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shifts as $shift)
                                <tr>
                                    <td class="fw-bold">{{ $shift->name }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ date('h:i A', strtotime($shift->start_time)) }}</span></td>
                                    <td><span class="badge bg-light text-dark border">{{ date('h:i A', strtotime($shift->end_time)) }}</span></td>
                                    <td class="small text-muted">
                                        {{ \Carbon\Carbon::parse($shift->start_time)->diffInHours(\Carbon\Carbon::parse($shift->end_time)) }} Hours
                                    </td>
                                    <td class="text-end">
                                        <button wire:click="openModal({{ $shift->id }})" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="ri-pencil-line"></i>
                                        </button>
                                        <button onclick="confirm('Delete this shift?') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $shift->id }})" class="btn btn-sm btn-outline-danger">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No shifts found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $shifts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-primary">{{ $shiftId ? 'Edit Shift' : 'Create Shift' }}</h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Shift Name <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Morning Shift">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Start Time <span class="text-danger">*</span></label>
                                <input type="time" wire:model="start_time" class="form-control @error('start_time') is-invalid @enderror">
                                @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">End Time <span class="text-danger">*</span></label>
                                <input type="time" wire:model="end_time" class="form-control @error('end_time') is-invalid @enderror">
                                @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="alert alert-info py-2 mb-0">
                            <small><i class="bi bi-info-circle me-1"></i> These shifts act as parent containers for doctor daily schedules.</small>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary px-4 shadow-none" wire:click="$set('showModal', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            {{ $shiftId ? 'Update Shift' : 'Save Shift' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>