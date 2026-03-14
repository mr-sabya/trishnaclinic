<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                    <h5 class="mb-0">Admin Departments</h5>
                    <button wire:click="openModal" class="btn btn-primary btn-sm">
                        <i class="ri-add-line"></i> Add Department
                    </button>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-1">
                            <select wire:model.live="perPage" class="form-select form-select-sm">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="col-md-8"></div>
                        <div class="col-md-3">
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Search departments...">
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($departments as $dept)
                                <tr>
                                    <td class="fw-bold">{{ $dept->name }}</td>
                                    <td class="text-muted">{{ Str::limit($dept->description, 50) }}</td>
                                    <td>
                                        <span class="badge {{ $dept->status ? 'bg-success' : 'bg-danger' }}">
                                            {{ $dept->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button wire:click="openModal({{ $dept->id }})" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="ri-pencil-line"></i>
                                        </button>
                                        <button onclick="confirm('Delete this department?') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $dept->id }})" class="btn btn-sm btn-outline-danger">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No departments found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $departments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $deptId ? 'Edit Department' : 'Create Department' }}</h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Department Name <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Accounts">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea wire:model="description" class="form-control" rows="3" placeholder="Brief description..."></textarea>
                        </div>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" wire:model="status" id="deptStatus">
                            <label class="form-check-label" for="deptStatus">Active Status</label>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            {{ $deptId ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>