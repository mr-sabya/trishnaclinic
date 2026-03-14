<div>
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-grid-3x3-gap me-2"></i>Charge Types</h5>
            <button wire:click="openModal" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="bi bi-plus-lg"></i> Add Charge Type
            </button>
        </div>

        <div class="card-body">
            <!-- Search Control -->
            <div class="row mb-3 align-items-center">
                <div class="col-md-1">
                    <select wire:model.live="perPage" class="form-select form-select-sm shadow-none">
                        <option value="10">10</option>
                        <option value="25">25</option>
                    </select>
                </div>
                <div class="col-md-8"></div>
                <div class="col-md-3">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm shadow-none" placeholder="Search charge type...">
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th style="cursor:pointer" wire:click="setSortBy('name')">
                                Type Name {!! $sortBy == 'name' ? ($sortDir == 'ASC' ? '↑' : '↓') : '' !!}
                            </th>
                            <th>Applicable Modules</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($chargeTypes as $type)
                        <tr>
                            <td class="fw-bold">{{ $type->name }}</td>
                            <td>
                                @foreach($type->modules as $mod)
                                <span class="badge bg-light text-dark border me-1 small shadow-sm">
                                    {{ strtoupper($mod) }}
                                </span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge {{ $type->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $type->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button wire:click="openModal({{ $type->id }})" class="btn btn-sm btn-outline-primary shadow-sm">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button onclick="confirm('Delete this type?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $type->id }})" class="btn btn-sm btn-outline-danger shadow-sm ms-1">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">No Charge Types found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $chargeTypes->links() }}</div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold">{{ $typeId ? 'Edit Charge Type' : 'New Charge Type' }}</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Type Name <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Procedures, Investigations, Bed Charges">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold d-block mb-3">Applicable Modules (Multi-select)</label>
                            <div class="row g-2">
                                @foreach($modules as $module)
                                <div class="col-6">
                                    <div class="form-check p-2 border rounded bg-light-subtle">
                                        <input class="form-check-input ms-0 me-2" type="checkbox"
                                            wire:model="selectedModules"
                                            value="{{ $module->value }}"
                                            id="mod_{{ $module->value }}">
                                        <label class="form-check-label small fw-semibold" for="mod_{{ $module->value }}">
                                            {{ strtoupper($module->value) }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('selectedModules') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>

                        <hr>

                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" wire:model="status" id="typeStatus">
                            <label class="form-check-label fw-bold" for="typeStatus">Active Status</label>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-3">
                        <button type="button" class="btn btn-secondary px-4 shadow-sm" wire:click="$set('showModal', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            {{ $typeId ? 'Update' : 'Save Charge Type' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>