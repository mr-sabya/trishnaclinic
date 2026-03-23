<div>
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-tags me-2"></i>Radiology Categories</h5>
            <button wire:click="openModal" class="btn btn-primary btn-sm px-3">
                <i class="bi bi-plus-lg"></i> Add Category
            </button>
        </div>

        <div class="card-body">
            <!-- Table Controls -->
            <div class="row mb-3 align-items-center">
                <div class="col-md-1">
                    <select wire:model.live="perPage" class="form-select form-select-sm">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="col-md-8 text-center">
                    @if (session()->has('success'))
                    <div class="alert alert-success py-1 px-3 mb-0 d-inline-block small shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    </div>
                    @endif
                    @if (session()->has('error'))
                    <div class="alert alert-danger py-1 px-3 mb-0 d-inline-block small shadow-sm">
                        <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                    </div>
                    @endif
                </div>
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Search categories...">
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
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td class="fw-semibold">{{ $category->name }}</td>
                            <td class="text-end">
                                <button wire:click="openModal({{ $category->id }})" class="btn btn-sm btn-outline-primary shadow-sm me-1">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button onclick="confirm('Are you sure you want to delete this category?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $category->id }})" class="btn btn-sm btn-outline-danger shadow-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center py-4 text-muted">No radiology categories found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary p-3">
                    <h5 class="modal-title fw-bold text-white">{{ $categoryId ? 'Edit Category' : 'Create New Category' }}</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. X-Ray, Ultrasound, CT Scan, MRI">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="alert alert-info py-2 mb-0 border-0 shadow-sm">
                            <small><i class="bi bi-info-circle me-1"></i> Categories help group radiology tests in patient billing and medical reports.</small>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary px-4" wire:click="$set('showModal', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            {{ $categoryId ? 'Update' : 'Save Category' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>