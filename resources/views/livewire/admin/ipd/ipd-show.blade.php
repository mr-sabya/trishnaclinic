<div class="container-fluid py-4">
    <!-- Notifications -->
    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Patient Header Summary -->
    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($ipd->patient->user->name) }}&background=0d6efd&color=fff" class="rounded-circle border border-3 border-light shadow-sm" width="80">
                    </div>
                    <div class="flex-grow-1 ms-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h3 class="mb-0 fw-bold text-dark">{{ $ipd->patient->user->name }}</h3>
                                <div class="mt-1">
                                    <span class="badge bg-primary px-3 py-2 fs-6 shadow-sm">{{ $ipd->ipd_number }}</span>
                                    <span class="ms-2 text-muted fw-semibold">MRN: {{ $ipd->patient->mrn_number }}</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-soft-info text-info border border-info px-3 py-2">
                                    <i class="bi bi-door-open-fill me-1"></i> {{ $ipd->bed->name }} ({{ $ipd->bed->bedGroup->name }})
                                </span>
                                <div class="small text-muted mt-1">{{ $ipd->bed->bedGroup->floor->name }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 {{ $ipd->balance > 0 ? 'bg-danger' : 'bg-success' }} text-white">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-0 small text-uppercase fw-bold">Outstanding Balance</p>
                            <h2 class="mb-0 fw-bold">৳ {{ number_format($ipd->balance, 2) }}</h2>
                        </div>
                        <i class="bi bi-wallet2 fs-1 opacity-25"></i>
                    </div>
                    <div class="mt-3 pt-2 border-top border-white-50 small d-flex justify-content-between">
                        <span>Grand Total: ৳{{ number_format($ipd->grand_total, 2) }}</span>
                        <span>Paid: ৳{{ number_format($ipd->total_paid, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white border-0 pt-3 px-4">
            <ul class="nav nav-tabs card-header-tabs border-bottom-0">
                <li class="nav-item">
                    <button wire:click="$set('activeTab', 'overview')" class="nav-link border-0 {{ $activeTab == 'overview' ? 'active fw-bold text-primary border-bottom border-primary border-3' : 'text-muted' }} px-4 py-3">
                        <i class="bi bi-info-circle me-2"></i>Overview
                    </button>
                </li>
                <li class="nav-item">
                    <button wire:click="$set('activeTab', 'charges')" class="nav-link border-0 {{ $activeTab == 'charges' ? 'active fw-bold text-primary border-bottom border-primary border-3' : 'text-muted' }} px-4 py-3">
                        <i class="bi bi-receipt me-2"></i>Charges ({{ $ipd->charges->count() }})
                    </button>
                </li>
                <li class="nav-item">
                    <button wire:click="$set('activeTab', 'payments')" class="nav-link border-0 {{ $activeTab == 'payments' ? 'active fw-bold text-primary border-bottom border-primary border-3' : 'text-muted' }} px-4 py-3">
                        <i class="bi bi-cash-stack me-2"></i>Payments ({{ $ipd->payments->count() }})
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4 bg-white">
            @if($activeTab == 'overview')
            <div class="row g-4">
                <div class="col-md-7 border-end">
                    <h6 class="fw-bold mb-4 text-uppercase small text-muted border-bottom pb-2">Admission & Clinical Details</h6>
                    <div class="row g-3">
                        <div class="col-6 mb-3">
                            <label class="text-muted d-block small">Admitting Consultant</label>
                            <span class="fw-bold text-dark">{{ $ipd->doctor->name }}</span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted d-block small">Admission Date</label>
                            <span class="fw-bold">{{ $ipd->admission_date->format('d M Y, h:i A') }}</span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted d-block small">Case Type / Severity</label>
                            <span class="badge bg-light text-dark border">{{ $ipd->case_type }}</span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted d-block small">Casualty/Emergency</label>
                            <span class="fw-bold {{ $ipd->is_casualty ? 'text-danger' : 'text-success' }}">
                                {{ $ipd->is_casualty ? 'YES' : 'NO' }}
                            </span>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="text-muted d-block small">Documented Symptoms</label>
                            @foreach($ipd->symptoms as $sym)
                            <span class="badge bg-soft-primary text-primary border me-1">{{ $sym->title->title }}</span>
                            @endforeach
                        </div>
                        <div class="col-12">
                            <label class="text-muted d-block small">Note</label>
                            <p class="text-dark small bg-light p-2 rounded">{{ $ipd->note ?? 'No internal notes recorded.' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <h6 class="fw-bold mb-4 text-uppercase small text-muted border-bottom pb-2">Financial Summary</h6>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Doctor/Consultation Fee</span>
                            <span class="fw-bold">৳ {{ number_format($ipd->doctor_fee, 2) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Hospital/Admission Fee</span>
                            <span class="fw-bold">৳ {{ number_format($ipd->hospital_fee, 2) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Additional Service Charges</span>
                            <span class="fw-bold">৳ {{ number_format($ipd->charges->sum('net_amount'), 2) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0 bg-light py-3 px-2">
                            <span class="fw-bold text-dark">Grand Total Bill</span>
                            <span class="fw-bold text-primary fs-5">৳ {{ number_format($ipd->grand_total, 2) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0 border-0 pt-3">
                            <span class="text-success fw-bold">Total Paid to Date</span>
                            <span class="text-success fw-bold">৳ {{ number_format($ipd->total_paid, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @elseif($activeTab == 'charges')
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0 text-dark">Service & Treatment Charges</h6>
                <button wire:click="$set('showChargeModal', true)" class="btn btn-primary btn-sm px-4 shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Add Service Charge
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th>Date</th>
                            <th>Charge Description</th>
                            <th>Category</th>
                            <th class="text-end">Applied Rate</th>
                            <th class="text-end">Tax (%)</th>
                            <th class="text-end">Net Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ipd->charges as $charge)
                        <tr>
                            <td class="small">{{ $charge->created_at->format('d M, Y h:i A') }}</td>
                            <td class="fw-bold text-dark">{{ $charge->chargeMaster->name }}</td>
                            <td><span class="badge bg-light text-muted border small">{{ $charge->chargeMaster->category->name ?? 'N/A' }}</span></td>
                            <td class="text-end">৳ {{ number_format($charge->applied_charge, 2) }}</td>
                            <td class="text-end text-muted">{{ $charge->tax_percentage }}%</td>
                            <td class="text-end fw-bold text-primary">৳ {{ number_format($charge->net_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted small italic">No additional charges recorded for this admission.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($ipd->charges->count() > 0)
                    <tfoot class="table-light fw-bold border-top">
                        <tr>
                            <td colspan="5" class="text-end text-dark">Subtotal Charges:</td>
                            <td class="text-end text-primary fs-6">৳ {{ number_format($ipd->charges->sum('net_amount'), 2) }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            @elseif($activeTab == 'payments')
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0 text-dark">Transaction & Deposit History</h6>
                <button wire:click="$set('showPaymentModal', true)" class="btn btn-success btn-sm px-4 shadow-sm">
                    <i class="bi bi-wallet2 me-1"></i> Record Payment
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th>Date</th>
                            <th>Reference No</th>
                            <th>Method</th>
                            <th>Note</th>
                            <th class="text-end">Amount Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ipd->payments as $payment)
                        <tr>
                            <td class="small">{{ $payment->created_at->format('d M, Y h:i A') }}</td>
                            <td><code class="text-dark fw-bold">{{ $payment->cheque_no ?: 'CASH-REC-'.$payment->id }}</code></td>
                            <td><span class="badge bg-soft-success text-success border border-success px-3">{{ $payment->method->name }}</span></td>
                            <td class="small text-muted">{{ $payment->note ?: '-' }}</td>
                            <td class="text-end fw-bold text-success fs-6">৳ {{ number_format($payment->paid_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted small italic">No payments have been received yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($ipd->payments->count() > 0)
                    <tfoot class="table-light fw-bold border-top">
                        <tr>
                            <td colspan="4" class="text-end text-dark">Total Collections:</td>
                            <td class="text-end text-success fs-6">৳ {{ number_format($ipd->total_paid, 2) }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            @endif
        </div>
    </div>

    <!-- MODAL: ADD SERVICE CHARGE -->
    @if($showChargeModal)
    <div class="modal fade show d-block shadow-lg" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <form wire:submit.prevent="addCharge" class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Add Service Charge</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showChargeModal', false)"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Charge Category</label>
                        <select wire:model.live="charge_category_id" class="form-select shadow-none">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Charge Item</label>
                        <select wire:model.live="charge_id" class="form-select shadow-none @error('charge_id') is-invalid @enderror">
                            <option value="">Select Item</option>
                            @foreach($charges as $c) <option value="{{ $c->id }}">{{ $c->name }} (৳{{ $c->standard_charge }})</option> @endforeach
                        </select>
                        @error('charge_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-primary">Applied Rate (৳)</label>
                            <input type="number" wire:model.live="applied_charge" class="form-control shadow-none fw-bold @error('applied_charge') is-invalid @enderror">
                            @error('applied_charge') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Tax ({{ $tax_percentage }}%)</label>
                            <input type="text" value="৳ {{ number_format($tax_amount, 2) }}" class="form-control bg-light" readonly>
                        </div>
                    </div>
                    <div class="bg-light p-3 rounded text-center border">
                        <small class="text-muted text-uppercase fw-bold">Net Payable Amount</small>
                        <h3 class="mb-0 fw-bold text-primary">৳ {{ number_format($net_amount, 2) }}</h3>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 p-3">
                    <button type="button" class="btn btn-secondary px-4 shadow-sm" wire:click="$set('showChargeModal', false)">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Confirm & Save Charge</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- MODAL: RECORD PAYMENT -->
    @if($showPaymentModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <form wire:submit.prevent="addPayment" class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success text-white py-3">
                    <h5 class="modal-title fw-bold"><i class="bi bi-wallet2 me-2"></i>Record Deposit / Payment</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showPaymentModal', false)"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4 text-center">
                        <label class="form-label fs-5 fw-bold text-dark">Collection Amount (৳)</label>
                        <input type="number" wire:model="paid_amount" class="form-control form-control-lg text-center fw-bold border-success text-success shadow-none @error('paid_amount') is-invalid @enderror" placeholder="0.00">
                        @error('paid_amount') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Payment Method</label>
                            <select wire:model.live="payment_method_id" class="form-select shadow-none">
                                @foreach($paymentMethods as $pm) <option value="{{ $pm->id }}">{{ $pm->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Cheque/Ref No.</label>
                            <input type="text" wire:model="cheque_no" class="form-control shadow-none" placeholder="Optional">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Note</label>
                            <textarea wire:model="note" class="form-control shadow-none" rows="2" placeholder="Describe the transaction..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 p-3">
                    <button type="button" class="btn btn-secondary px-4 shadow-sm" wire:click="$set('showPaymentModal', false)">Cancel</button>
                    <button type="submit" class="btn btn-success px-4 shadow-sm">Confirm Payment Receipt</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>