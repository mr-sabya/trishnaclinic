<div>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary"><i class="ri-heart-pulse-line me-2"></i>Symptom Types</h5>
                    <button wire:click="openModal" class="btn btn-primary btn-sm px-3 shadow-sm">
                        <i class="ri-add-line"></i> Add Type
                    </button>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-1">
                            <select wire:model.live="perPage" class="form-select form-select-sm shadow-none">
                                <option value="10">10</option>
                                <option value="25">25</option>
                            </select>
                        </div>
                        <div class="col-md-8"></div>
                        <div class="col-md-3">
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm shadow-none" placeholder="Search types...">
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="table-light">
                                <tr>
                                    <th width="10%">ID</th>
                                    <th>Symptom Category Name</th>
                                    <th>Created At</th>
                                    <th class="text-end" width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($types as $type)
                                <tr>
                                    <td>#{{ $type->id }}</td>
                                    <td class="fw-bold">{{ $type->name }}</td>
                                    <td class="text-muted small">{{ $type->created_at->format('d M, Y') }}</td>
                                    <td class="text-end">
                                        <button wire:click="openModal({{ $type->id }})" class="btn btn-sm btn-outline-primary me-1 shadow-none">
                                            <i class="ri-pencil-line"></i>
                                        </button>
                                        <button onclick="confirm('Delete this symptom type?') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $type->id }})" class="btn btn-sm btn-outline-danger shadow-none">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No symptom types found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $types->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-primary">{{ $typeId ? 'Edit Symptom Type' : 'Create Symptom Type' }}</h5>
                    <button type="button" class="btn-close shadow-none" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Symptom Type Name <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control shadow-none @error('name') is-invalid @enderror" placeholder="e.g. Respiratory, Cardiac, Gastric">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary px-4 shadow-none" wire:click="$set('showModal', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            {{ $typeId ? 'Update Changes' : 'Save Type' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>