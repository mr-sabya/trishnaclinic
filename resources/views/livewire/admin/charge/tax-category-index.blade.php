<div>
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-percent me-2"></i>Tax Categories</h5>
            <button wire:click="openModal" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="bi bi-plus-lg"></i> Add Tax
            </button>
        </div>

        <div class="card-body">
            <!-- Search and Pagination Controls -->
            <div class="row mb-3 align-items-center">
                <div class="col-md-1">
                    <select wire:model.live="perPage" class="form-select form-select-sm shadow-none">
                        <option value="10">10</option>
                        <option value="25">25</option>
                    </select>
                </div>
                <div class="col-md-8"></div>
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 shadow-none" placeholder="Search tax name...">
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th style="cursor:pointer" wire:click="setSortBy('name')">
                                Name {!! $sortBy == 'name' ? ($sortDir == 'ASC' ? '↑' : '↓') : '' !!}
                            </th>
                            <th style="cursor:pointer" wire:click="setSortBy('percentage')">
                                Percentage (%) {!! $sortBy == 'percentage' ? ($sortDir == 'ASC' ? '↑' : '↓') : '' !!}
                            </th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($taxes as $tax)
                        <tr>
                            <td class="fw-bold">{{ $tax->name }}</td>
                            <td>
                                <span class="badge bg-soft-success text-success px-3">
                                    {{ number_format($tax->percentage, 2) }}%
                                </span>
                            </td>
                            <td class="text-end">
                                <button wire:click="openModal({{ $tax->id }})" class="btn btn-sm btn-outline-primary shadow-sm me-1">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button onclick="confirm('Are you sure you want to delete this tax category?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $tax->id }})" class="btn btn-sm btn-outline-danger shadow-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="bi bi-receipt d-block mb-2 fs-2"></i>
                                No tax categories found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $taxes->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold">
                        {{ $taxId ? 'Update Tax Category' : 'New Tax Category' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Tax Name <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control shadow-none @error('name') is-invalid @enderror" placeholder="e.g. VAT, Service Tax, GST">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            <div class="form-text mt-1 small">Visible to patients on invoices.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tax Percentage (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" wire:model="percentage" class="form-control shadow-none @error('percentage') is-invalid @enderror" placeholder="0.00">
                                <span class="input-group-text bg-light fw-bold">%</span>
                            </div>
                            @error('percentage') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="bg-light p-3 rounded border">
                            <div class="d-flex">
                                <i class="bi bi-info-circle-fill text-primary me-2 mt-1"></i>
                                <div class="small text-muted">
                                    Setting tax to <strong>0%</strong> is useful for non-taxable procedures or services.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-3">
                        <button type="button" class="btn btn-light px-4 border" wire:click="$set('showModal', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            {{ $taxId ? 'Update Tax' : 'Save Tax' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <style>
        .bg-soft-success {
            background-color: #e8f5e9;
        }
    </style>
</div>