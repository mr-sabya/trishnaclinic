<div>
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-list-check me-2"></i>Pathology Parameters</h5>
            <button wire:click="openModal" class="btn btn-primary btn-sm px-3">
                <i class="bi bi-plus-lg"></i> Add Parameter
            </button>
        </div>

        <div class="card-body">
            <!-- Search & Filter -->
            <div class="row mb-3">
                <div class="col-md-1">
                    <select wire:model.live="perPage" class="form-select form-select-sm">
                        <option value="10">10</option>
                        <option value="25">25</option>
                    </select>
                </div>
                <div class="col-md-8">
                    @if (session()->has('success')) <span class="badge bg-success">{{ session('success') }}</span> @endif
                    @if (session()->has('error')) <span class="badge bg-danger">{{ session('error') }}</span> @endif
                </div>
                <div class="col-md-3">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Search parameters...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th style="cursor:pointer" wire:click="setSortBy('parameter_name')">
                                Parameter Name {!! $sortBy == 'parameter_name' ? ($sortDir == 'ASC' ? '↑' : '↓') : '' !!}
                            </th>
                            <th>Reference Range</th>
                            <th>Unit</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parameters as $param)
                        <tr>
                            <td class="fw-semibold">{{ $param->parameter_name }}</td>
                            <td>
                                @if($param->reference_range_from || $param->reference_range_to)
                                <span class="text-muted">{{ $param->reference_range_from }} - {{ $param->reference_range_to }}</span>
                                @else
                                <span class="text-muted small">Not set</span>
                                @endif
                            </td>
                            <td><span class="badge bg-info text-dark">{{ $param->unit->name ?? 'N/A' }}</span></td>
                            <td class="text-end">
                                <button wire:click="openModal({{ $param->id }})" class="btn btn-sm btn-outline-primary shadow-sm">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button onclick="confirm('Delete this parameter?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $param->id }})" class="btn btn-sm btn-outline-danger shadow-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No parameters found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $parameters->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary p-3">
                    <h5 class="modal-title text-white">{{ $parameterId ? 'Edit Parameter' : 'New Parameter' }}</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold">Parameter Name <span class="text-danger">*</span></label>
                                <input type="text" wire:model="parameter_name" class="form-control @error('parameter_name') is-invalid @enderror">
                                @error('parameter_name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Unit <span class="text-danger">*</span></label>
                                <select wire:model="pathology_unit_id" class="form-select @error('pathology_unit_id') is-invalid @enderror">
                                    <option value="">Select Unit</option>
                                    @foreach($units as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                                @error('pathology_unit_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Reference Range (From)</label>
                                <input type="text" wire:model="reference_range_from" class="form-control" placeholder="e.g. 13.5">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Reference Range (To)</label>
                                <input type="text" wire:model="reference_range_to" class="form-control" placeholder="e.g. 17.5">
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">Description/Notes</label>
                            <textarea wire:model="description" class="form-control" rows="3" placeholder="Additional details about this parameter..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary px-4" wire:click="$set('showModal', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            {{ $parameterId ? 'Update' : 'Save Parameter' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>