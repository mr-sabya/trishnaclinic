<div>
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-currency-dollar me-2"></i>Charge Master</h5>
            <button wire:click="openModal" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="bi bi-plus-lg"></i> Add New Charge
            </button>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Search by name or code...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th>Charge Name</th>
                            <th>Category / Type</th>
                            <th>Unit</th>
                            <th>Tax</th>
                            <th class="text-end">Std. Charge</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($charges as $charge)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $charge->name }}</div>
                                <small class="text-muted">{{ $charge->code }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $charge->category->name }}</span>
                                <div class="small text-muted">{{ $charge->category->chargeType->name }}</div>
                            </td>
                            <td>{{ $charge->unit->short_name }}</td>
                            <td>{{ $charge->tax->name }} ({{ number_format($charge->tax->percentage, 0) }}%)</td>
                            <td class="text-end fw-bold text-primary">{{ number_format($charge->standard_charge, 2) }}</td>
                            <td class="text-end">
                                <button wire:click="openModal({{ $charge->id }})" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No charges found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $charges->links() }}</div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">{{ $chargeId ? 'Edit Charge' : 'Create New Charge' }}</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <!-- Left Side: Basic Info -->
                            <div class="col-md-7 border-end pe-4">
                                <h6 class="fw-bold mb-3 border-bottom pb-2">Service Details</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Charge Type *</label>
                                        <select wire:model.live="charge_type_id" class="form-select shadow-none">
                                            <option value="">Select Type</option>
                                            @foreach($chargeTypes as $type) <option value="{{ $type->id }}">{{ $type->name }}</option> @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Category *</label>
                                        <select wire:model="charge_category_id" class="form-select shadow-none">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label small fw-bold">Charge Name *</label>
                                        <input type="text" wire:model="name" class="form-control shadow-none">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Charge Code *</label>
                                        <input type="text" wire:model="code" class="form-control shadow-none" placeholder="e.g. CBC01">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Unit *</label>
                                        <select wire:model="unit_id" class="form-select shadow-none">
                                            <option value="">Select Unit</option>
                                            @foreach($units as $unit) <option value="{{ $unit->id }}">{{ $unit->name }}</option> @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Tax Category *</label>
                                        <select wire:model="tax_category_id" class="form-select shadow-none">
                                            <option value="">Select Tax</option>
                                            @foreach($taxes as $tax) <option value="{{ $tax->id }}">{{ $tax->name }} ({{ $tax->percentage }}%)</option> @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label small fw-bold">Standard Charge ($) *</label>
                                        <input type="number" step="0.01" wire:model="standard_charge" class="form-control form-control-lg fw-bold text-primary">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label small fw-bold">Description</label>
                                        <textarea wire:model="description" class="form-control shadow-none" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side: TPA Pricing -->
                            <div class="col-md-5 ps-4">
                                <h6 class="fw-bold mb-3 border-bottom pb-2">TPA / Insurance Pricing (Overrides)</h6>
                                <p class="small text-muted mb-3">Leave blank to use the standard charge for that TPA.</p>
                                <div style="max-height: 400px; overflow-y: auto;">
                                    @foreach($tpas as $tpa)
                                    <div class="mb-3 p-2 bg-light rounded border">
                                        <label class="form-label small fw-bold mb-1">{{ $tpa->name }} ({{ $tpa->code }})</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white">$</span>
                                            <input type="number" step="0.01" wire:model="tpa_prices.{{ $tpa->id }}" class="form-control shadow-none" placeholder="Override amount">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary px-4" wire:click="$set('showModal', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Save Charge</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>