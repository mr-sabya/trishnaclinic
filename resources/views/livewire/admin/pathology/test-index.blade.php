<div>
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="bi bi-microscope me-2"></i>Pathology Test Directory
            </h5>
            <button wire:click="openModal" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="bi bi-plus-lg"></i> Add Pathology Test
            </button>
        </div>

        <div class="card-body">
            <!-- Flash Messages -->
            @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show small py-2" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Table Controls -->
            <div class="row mb-3 align-items-center">
                <div class="col-md-1">
                    <select wire:model.live="perPage" class="form-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="col-md-8"></div>
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Search tests...">
                    </div>
                </div>
            </div>

            <!-- Main Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th style="cursor:pointer" wire:click="setSortBy('test_name')">
                                Test Name {!! $sortBy == 'test_name' ? ($sortDir == 'ASC' ? '↑' : '↓') : '' !!}
                            </th>
                            <th>Short Name</th>
                            <th>Category</th>
                            <th>Method</th>
                            <th class="text-end">Amount (৳)</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tests as $test)
                        <tr>
                            <td>
                                <span class="fw-bold text-dark">{{ $test->test_name }}</span>
                                <div class="text-muted small">{{ $test->test_type }}</div>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $test->short_name }}</span></td>
                            <td>{{ $test->category->name ?? 'N/A' }}</td>
                            <td><small class="text-muted">{{ $test->method ?: '--' }}</small></td>
                            <td class="text-end fw-bold text-primary">
                                {{ number_format($test->total_amount, 2) }}
                            </td>
                            <td class="text-end">
                                <button wire:click="openModal({{ $test->id }})" class="btn btn-sm btn-outline-primary shadow-sm me-1">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button onclick="confirm('Are you sure you want to delete this test?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $test->id }})" class="btn btn-sm btn-outline-danger shadow-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No pathology tests found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $tests->links() }}
            </div>
        </div>
    </div>

    <!-- Full Screen Modal (Matches your specific form layout) -->
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); overflow-y: auto;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary p-3">
                    <h5 class="modal-title fw-bold text-white">
                        <i class="bi bi-plus-circle me-2"></i>{{ $testId ? 'Edit Pathology Test' : 'Add Pathology Test' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showModal', false)"></button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <!-- Basic Test Details -->
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Test Name <span class="text-danger">*</span></label>
                                <input type="text" wire:model="test_name" class="form-control @error('test_name') is-invalid @enderror">
                                @error('test_name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Short Name <span class="text-danger">*</span></label>
                                <input type="text" wire:model="short_name" class="form-control @error('short_name') is-invalid @enderror">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Test Type</label>
                                <input type="text" wire:model="test_type" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Category Name <span class="text-danger">*</span></label>
                                <select wire:model="pathology_category_id" class="form-select @error('pathology_category_id') is-invalid @enderror">
                                    <option value="">Select</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Sub Category</label>
                                <input type="text" wire:model="sub_category" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Method</label>
                                <input type="text" wire:model="method" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Report Days <span class="text-danger">*</span></label>
                                <input type="number" wire:model="report_days" class="form-control @error('report_days') is-invalid @enderror">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Charge Category <span class="text-danger">*</span></label>
                                <select wire:model.live="charge_category_id" class="form-select">
                                    <option value="">Select</option>
                                    @foreach($chargeCategories as $ccat)
                                    <option value="{{ $ccat->id }}">{{ $ccat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Dynamic Charges Logic -->
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Charge Name <span class="text-danger">*</span></label>
                                <select wire:model.live="charge_id" class="form-select @error('charge_id') is-invalid @enderror">
                                    <option value="">Select</option>
                                    @foreach($availableCharges as $charge)
                                    <option value="{{ $charge->id }}">{{ $charge->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted">Tax (%)</label>
                                <div class="input-group">
                                    <input type="text" wire:model="tax" class="form-control bg-light" readonly>
                                    <span class="input-group-text bg-light">%</span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted">Standard Charge (৳)</label>
                                <input type="text" wire:model="standard_charge" class="form-control bg-light" readonly>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-primary">Total Amount (৳)</label>
                                <input type="text" wire:model="amount" class="form-control bg-light border-primary fw-bold" readonly>
                            </div>
                        </div>

                        <!-- Dynamic Parameters Table Section -->
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0 text-secondary">Test Parameter Details</h6>
                                    <button type="button" wire:click="addParameterRow" class="btn btn-outline-info btn-sm">
                                        <i class="bi bi-plus-lg"></i> Add Row
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle shadow-sm">
                                        <thead class="table-light small">
                                            <tr>
                                                <th width="40%" class="ps-3">Test Parameter Name <span class="text-danger">*</span></th>
                                                <th width="25%">Reference Range</th>
                                                <th width="25%">Unit</th>
                                                <th width="10%" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($testParameters as $index => $row)
                                            <tr wire:key="param-row-{{ $index }}">
                                                <td class="p-2">
                                                    <select wire:model.live="testParameters.{{ $index }}.parameter_id"
                                                        class="form-select @error('testParameters.'.$index.'.parameter_id') is-invalid @enderror">
                                                        <option value="">Select Parameter</option>
                                                        @foreach($allParameters as $p)
                                                        <option value="{{ $p->id }}">{{ $p->parameter_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="p-2">
                                                    <input type="text" wire:model="testParameters.{{ $index }}.reference_range"
                                                        class="form-control bg-light text-muted" readonly>
                                                </td>
                                                <td class="p-2">
                                                    <input type="text" wire:model="testParameters.{{ $index }}.unit"
                                                        class="form-control bg-light text-muted" readonly>
                                                </td>
                                                <td class="text-center p-2">
                                                    <button type="button" wire:click="removeParameterRow({{ $index }})"
                                                        class="btn btn-sm btn-outline-danger border-0">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @error('testParameters.*.parameter_id')
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-triangle me-1"></i>Please select a parameter for all rows.</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary px-4 shadow-sm" wire:click="$set('showModal', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            <i class="bi bi-check-circle me-1"></i> {{ $testId ? 'Update' : 'Save' }} Pathology Test
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>