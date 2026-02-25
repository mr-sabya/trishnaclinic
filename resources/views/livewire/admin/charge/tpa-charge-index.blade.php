<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-shield-check me-2"></i>TPA Price List Manager</h5>
                </div>
                <div class="col-md-8 text-end">
                    @if($selectedTpa)
                    <button wire:click="savePrices" class="btn btn-success px-4 shadow-sm">
                        <span wire:loading wire:target="savePrices" class="spinner-border spinner-border-sm me-1"></span>
                        <i class="bi bi-save me-1"></i> Save All Changes
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body bg-light-subtle border-bottom">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="small fw-bold text-muted">Select TPA / Insurance *</label>
                    <select wire:model.live="selectedTpa" class="form-select border-primary shadow-sm">
                        <option value="">-- Choose Organization --</option>
                        @foreach($tpas as $tpa)
                        <option value="{{ $tpa->id }}">{{ $tpa->name }} ({{ $tpa->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold text-muted">Filter by Category</label>
                    <select wire:model.live="selectedCategory" class="form-select shadow-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold text-muted">Search Charge Name</label>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control shadow-sm" placeholder="Search...">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if(!$selectedTpa)
            <div class="text-center py-5">
                <i class="bi bi-arrow-up-circle fs-1 text-muted d-block mb-3"></i>
                <h6 class="text-muted">Please select a TPA organization to manage their specific pricing.</h6>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="35%">Charge Name</th>
                            <th width="15%">Unit</th>
                            <th width="20%" class="text-end">Standard Rate</th>
                            <th width="30%" class="text-end bg-primary">Negotiated TPA Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($charges as $charge)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $charge->name }}</div>
                                <small class="text-muted">{{ $charge->category->name }} | {{ $charge->code }}</small>
                            </td>
                            <td>{{ $charge->unit->short_name }}</td>
                            <td class="text-end fw-bold text-muted">
                                {{ number_format($charge->standard_charge, 2) }}
                            </td>
                            <td class="text-end bg-light">
                                <div class="input-group input-group-sm justify-content-end">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01"
                                        wire:model="prices.{{ $charge->id }}"
                                        class="form-control text-end fw-bold text-primary shadow-none"
                                        style="max-width: 150px;"
                                        placeholder="Use Standard">
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">No charges found for this selection.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                {{ $charges->links() }}
            </div>
            @endif
        </div>
    </div>

    <div class="mt-3 alert alert-info py-2">
        <small><i class="bi bi-info-circle-fill me-2"></i> Leaving the TPA rate <strong>blank</strong> will result in the system using the <strong>Standard Rate</strong> during billing for this organization.</small>
    </div>
</div>