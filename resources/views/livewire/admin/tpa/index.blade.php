<div>
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-primary"><i class="ri-building-line me-2"></i>TPA Management</h5>
            <button wire:click="openModal" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="ri-add-line"></i> Add New TPA
            </button>
        </div>

        <div class="card-body">
            <div class="row mb-3 g-2">
                <div class="col-md-1">
                    <select wire:model.live="perPage" class="form-select form-select-sm">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="col-md-8"></div>
                <div class="col-md-3 text-end">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="ri-search-line"></i></span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0" placeholder="Search TPA name or code...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th style="cursor:pointer" wire:click="setSortBy('name')">
                                TPA Name {!! $sortBy == 'name' ? ($sortDir == 'ASC' ? '↑' : '↓') : '' !!}
                            </th>
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
                            <td class="fw-bold text-dark">{{ $tpa->name }}</td>
                            <td><span class="badge bg-light text-primary border">{{ $tpa->code }}</span></td>
                            <td>{{ $tpa->contact_number }}</td>
                            <td>
                                <div class="small fw-bold">{{ $tpa->contact_person_name }}</div>
                                <div class="x-small text-muted">{{ $tpa->contact_person_phone }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $tpa->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $tpa->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button wire:click="openModal({{ $tpa->id }})" class="btn btn-outline-primary" title="Edit"><i class="ri-pencil-line"></i></button>
                                    <button onclick="confirm('Delete this TPA record?') || event.stopImmediatePropagation()" wire:click="delete({{ $tpa->id }})" class="btn btn-outline-danger" title="Delete"><i class="ri-trash-line"></i></button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="ri-building-2-line fs-2 d-block mb-2 text-light"></i>
                                No TPA records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3 d-flex justify-content-center">{{ $tpas->links() }}</div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1050;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary p-3">
                    <h5 class="modal-title fw-bold text-white "><i class="ri-edit-box-line me-2"></i>{{ $tpaId ? 'Edit TPA' : 'Create New TPA' }}</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body p-4 bg-light">
                        <!-- Inside the Modal Body -->
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label small fw-bold text-primary">TPA Name <span class="text-danger">*</span></label>
                                <input type="text" wire:model.live="name" class="form-control" placeholder="Enter Name">
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-primary">Contact No <span class="text-danger">*</span></label>
                                <input type="text" wire:model.live="contact_number" class="form-control" placeholder="Primary Phone">
                                @error('contact_number') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-primary">Short Code <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" wire:model="code" class="form-control fw-bold" placeholder="AUTO">
                                    <span class="input-group-text bg-white"><i class="ri-magic-line text-info"></i></span>
                                </div>
                                <small class="x-small text-muted italic">Auto-generated</small>
                                @error('code') <small class="text-danger d-block">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted">Address</label>
                                <input type="text" wire:model="address" class="form-control" placeholder="City, Area">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Contact Person Name</label>
                                <input type="text" wire:model="contact_person_name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Contact Person Phone</label>
                                <input type="text" wire:model="contact_person_phone" class="form-control">
                            </div>

                            <div class="col-12 mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" wire:model="status" id="tpaStatus">
                                    <label class="form-check-label fw-bold" for="tpaStatus">Active Status</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-top-0">
                        <button type="button" class="btn btn-light px-4" wire:click="$set('showModal', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm fw-bold">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            {{ $tpaId ? 'Update TPA' : 'Save TPA' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <style>
        .x-small {
            font-size: 0.75rem;
        }
    </style>
</div>