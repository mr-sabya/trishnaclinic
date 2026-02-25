<div>
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-tags me-2"></i>Charge Categories</h5>
            <button wire:click="openModal" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="bi bi-plus-lg"></i> Add Category
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
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 shadow-none" placeholder="Search categories...">
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th style="cursor:pointer" wire:click="setSortBy('name')">
                                Category Name {!! $sortBy == 'name' ? ($sortDir == 'ASC' ? '↑' : '↓') : '' !!}
                            </th>
                            <th>Charge Type</th>
                            <th>Description</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td class="fw-bold">{{ $category->name }}</td>
                            <td>
                                <span class="badge bg-soft-primary text-primary px-2 border border-primary">
                                    {{ $category->chargeType->name }}
                                </span>
                            </td>
                            <td class="small text-muted text-truncate" style="max-width: 250px;">
                                {{ $category->description ?? 'No description' }}
                            </td>
                            <td class="text-end">
                                <button wire:click="openModal({{ $category->id }})" class="btn btn-sm btn-outline-primary shadow-sm" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button onclick="confirm('Delete this category?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $category->id }})" class="btn btn-sm btn-outline-danger shadow-sm ms-1" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">No categories found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $categories->links() }}</div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold">{{ $categoryId ? 'Edit Category' : 'New Category' }}</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Charge Type <span class="text-danger">*</span></label>
                            <select wire:model="charge_type_id" class="form-select shadow-none @error('charge_type_id') is-invalid @enderror">
                                <option value="">-- Select Charge Type --</option>
                                @foreach($chargeTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('charge_type_id') <small class="text-danger">{{ $message }}</small> @enderror
                            <div class="form-text small">This determines which hospital modules this category belongs to.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control shadow-none @error('name') is-invalid @enderror" placeholder="e.g. ICU Charges, Blood Bank, X-Ray">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">Description</label>
                            <textarea wire:model="description" class="form-control shadow-none" rows="3" placeholder="Enter category details..."></textarea>
                            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-3">
                        <button type="button" class="btn btn-secondary px-4 shadow-sm" wire:click="$set('showModal', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            {{ $categoryId ? 'Update' : 'Save Category' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <style>
        .bg-soft-primary {
            background-color: #e3f2fd;
        }
    </style>
</div>