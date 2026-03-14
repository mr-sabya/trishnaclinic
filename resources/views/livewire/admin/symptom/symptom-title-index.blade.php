<div>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary"><i class="ri-list-check-2 me-2"></i>Symptom Titles</h5>
                    <button wire:click="openModal" class="btn btn-primary btn-sm px-3 shadow-sm">
                        <i class="ri-add-line"></i> Add Symptom Title
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
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm shadow-none" placeholder="Search by title or type...">
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="table-light">
                                <tr>
                                    <th>Symptom Title</th>
                                    <th>Symptom Type</th>
                                    <th>Description</th>
                                    <th class="text-end" width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($titles as $item)
                                <tr>
                                    <td class="fw-bold text-dark">{{ $item->title }}</td>
                                    <td>
                                        <span class="badge bg-soft-info text-info border border-info">
                                            {{ $item->type->name }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">{{ Str::limit($item->description, 50) }}</td>
                                    <td class="text-end">
                                        <button wire:click="openModal({{ $item->id }})" class="btn btn-sm btn-outline-primary me-1 shadow-none">
                                            <i class="ri-pencil-line"></i>
                                        </button>
                                        <button onclick="confirm('Delete this symptom title?') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $item->id }})" class="btn btn-sm btn-outline-danger shadow-none">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $titles->links() }}
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
                    <h5 class="modal-title fw-bold text-primary">{{ $titleId ? 'Edit Symptom Title' : 'New Symptom Title' }}</h5>
                    <button type="button" class="btn-close shadow-none" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Symptom Type <span class="text-danger">*</span></label>
                            <select wire:model="symptom_type_id" class="form-select shadow-none @error('symptom_type_id') is-invalid @enderror">
                                <option value="">Select Category</option>
                                @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('symptom_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Title <span class="text-danger">*</span></label>
                            <input type="text" wire:model="title" class="form-control shadow-none @error('title') is-invalid @enderror" placeholder="e.g. Sharp Chest Pain">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Description</label>
                            <textarea wire:model="description" class="form-control shadow-none" rows="3" placeholder="Additional details about this symptom..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary px-4 shadow-none" wire:click="$set('showModal', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            {{ $titleId ? 'Update Changes' : 'Save Title' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>