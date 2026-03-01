<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                    <h5 class="mb-0">Doctor Specialists</h5>
                    <button wire:click="openModal" class="btn btn-primary btn-sm">
                        <i class="ri-add-line"></i> Add Specialist
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
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Search specialists...">
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Specialist Name</th>
                                    <th>Created At</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($specialists as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td class="fw-bold text-dark">{{ $item->name }}</td>
                                    <td class="text-muted small">{{ $item->created_at->format('d M, Y') }}</td>
                                    <td class="text-end">
                                        <button wire:click="openModal({{ $item->id }})" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="ri-pencil-line"></i>
                                        </button>
                                        <button onclick="confirm('Delete this specialist?') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $item->id }})" class="btn btn-sm btn-outline-danger">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No specialists found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $specialists->links() }}
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
                    <h5 class="modal-title fw-bold text-primary">{{ $specialistId ? 'Edit Specialist' : 'Create Specialist' }}</h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Specialist Name <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Cardiologist">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text small mt-2">Examples: Orthopedic Surgeon, Gynecologist, Neurologist, etc.</div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary shadow-none" wire:click="$set('showModal', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            {{ $specialistId ? 'Update Specialist' : 'Save Specialist' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>