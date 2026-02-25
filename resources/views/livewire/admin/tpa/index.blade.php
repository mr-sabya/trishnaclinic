<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h5 class="mb-0">TPA Management (Insurance Organizations)</h5>
            <button wire:click="openModal" class="btn btn-primary btn-sm">
                <i class="ri-add-line"></i> Add TPA
            </button>
        </div>

        <div class="card-body">
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
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Search by name or code...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th style="cursor:pointer" wire:click="setSortBy('name')">Name {!! $sortBy == 'name' ? ($sortDir == 'ASC' ? '↑' : '↓') : '' !!}</th>
                            <th>Code</th>
                            <th>Contact No</th>
                            <th>Contact Person</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tpas as $tpa)
                        <tr>
                            <td class="fw-bold">{{ $tpa->name }}</td>
                            <td><span class="badge bg-light text-primary">{{ $tpa->code }}</span></td>
                            <td>{{ $tpa->contact_number }}</td>
                            <td>
                                <div>{{ $tpa->contact_person_name }}</div>
                                <small class="text-muted">{{ $tpa->contact_person_phone }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $tpa->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $tpa->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button wire:click="openModal({{ $tpa->id }})" class="btn btn-sm btn-outline-primary"><i class="ri-pencil-line"></i></button>
                                <button onclick="confirm('Delete TPA?') || event.stopImmediatePropagation()" wire:click="delete({{ $tpa->id }})" class="btn btn-sm btn-outline-danger"><i class="ri-trash-line"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No TPAs found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $tpas->links() }}</div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">{{ $tpaId ? 'Edit TPA' : 'Create New TPA' }}</h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name" class="form-control" placeholder="Insurance Name">
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Code <span class="text-danger">*</span></label>
                                <input type="text" wire:model="code" class="form-control" placeholder="e.g. METLIFE">
                                @error('code') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Contact No <span class="text-danger">*</span></label>
                                <input type="text" wire:model="contact_number" class="form-control" placeholder="Office Phone">
                                @error('contact_number') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea wire:model="address" class="form-control" rows="2" placeholder="Organization Address"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Person Name</label>
                                <input type="text" wire:model="contact_person_name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Person Phone</label>
                                <input type="text" wire:model="contact_person_phone" class="form-control">
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" wire:model="status" id="tpaStatus">
                                    <label class="form-check-label" for="tpaStatus">Active Organization</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            {{ $tpaId ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>