<div class="container-fluid py-4">
    <!-- Feedback Alerts -->
    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Header Summary -->
    <div class="row mb-4 g-3">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($opd->patient->user->name) }}&background=0d6efd&color=fff" class="rounded-circle me-3 border" width="70">
                        <div>
                            <h4 class="mb-0 fw-bold">{{ $opd->patient->user->name }}</h4>
                            <div class="mt-1">
                                <span class="badge bg-primary">{{ $opd->opd_number }}</span>
                                <span class="text-muted small ms-2">MRN: {{ $opd->patient->mrn_number }} | {{ $opd->patient->user->phone }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- NEW: PRINT BLANK PRESCRIPTION BUTTON -->
                    <a href="{{ route('admin.opd.print-blank', $opd->id) }}" target="_blank" class="btn btn-outline-dark shadow-sm fw-bold">
                        <i class="bi bi-printer-fill me-2"></i> Print Blank Prescription
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 {{ $opd->balance > 0 ? 'bg-danger' : 'bg-primary' }} text-white h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-white-50">Balance Due</small>
                        <h3 class="mb-0 fw-bold text-white">৳ {{ number_format($opd->balance, 2) }}</h3>
                    </div>
                    <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0 pt-3">
            <ul class="nav nav-tabs card-header-tabs border-bottom-0">
                <li class="nav-item"><button wire:click="$set('activeTab', 'overview')" class="nav-link border-0 {{ $activeTab == 'overview' ? 'active fw-bold text-primary border-bottom border-primary' : 'text-muted' }}">Overview</button></li>
                <li class="nav-item"><button wire:click="$set('activeTab', 'charges')" class="nav-link border-0 {{ $activeTab == 'charges' ? 'active fw-bold text-primary border-bottom border-primary' : 'text-muted' }}">Charges ({{ $opd->charges->count() }})</button></li>
                <li class="nav-item"><button wire:click="$set('activeTab', 'payments')" class="nav-link border-0 {{ $activeTab == 'payments' ? 'active fw-bold text-primary border-bottom border-primary' : 'text-muted' }}">Payments ({{ $opd->payments->count() }})</button></li>
            </ul>
        </div>

        <div class="card-body">
            @if($activeTab == 'overview')
            <div class="row g-4">
                <div class="col-md-6 border-end">
                    <h6 class="fw-bold mb-3 text-uppercase small text-muted">Clinical Info</h6>
                    <table class="table table-sm table-borderless small">
                        <tr>
                            <td class="text-muted" width="40%">Consultant:</td>
                            <td class="fw-bold">{{ $opd->doctor->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Admission Date:</td>
                            <td>{{ $opd->appointment_date->format('d M Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Case Type:</td>
                            <td><span class="badge bg-light text-dark border">{{ $opd->case_type }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Allergies:</td>
                            <td class="text-danger fw-bold">{{ $opd->known_allergies ?? 'None' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Symptoms:</td>
                            <td>{{ $opd->symptoms_description ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold mb-3 text-uppercase small text-muted">Initial Billing Summary</h6>
                    <table class="table table-sm table-borderless small">
                        <tr>
                            <td class="text-muted">Doctor Fee:</td>
                            <td class="text-end">৳{{ number_format($opd->doctor_fee, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Hospital Fee:</td>
                            <td class="text-end">৳{{ number_format($opd->hospital_fee, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Initial Discount ({{ $opd->discount_percentage }}%):</td>
                            <td class="text-end text-danger">- ৳{{ number_format($opd->discount_amount, 2) }}</td>
                        </tr>
                        <tr class="border-top">
                            <td class="fw-bold">Net Total:</td>
                            <td class="text-end fw-bold">৳{{ number_format($opd->net_amount, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @elseif($activeTab == 'charges')
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Billing Items</h6>
                <button wire:click="$set('showChargeModal', true)" class="btn btn-primary btn-sm px-3 shadow-none">+ Add Service Charge</button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle small border">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Charge Name</th>
                            <th>Applied Rate</th>
                            <th>Tax</th>
                            <th class="text-end">Net Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($opd->charges as $c)
                        <tr>
                            <td>{{ $c->created_at->format('d M, Y') }}</td>
                            <td class="fw-bold">{{ $c->chargeMaster->name }}</td>
                            <td>৳{{ number_format($c->applied_charge, 2) }}</td>
                            <td>৳{{ number_format($c->tax_amount, 2) }} ({{ $c->tax_percentage }}%)</td>
                            <td class="text-end fw-bold">৳{{ number_format($c->net_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No additional charges found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr class="fw-bold">
                            <td colspan="4" class="text-end">Grand Total of Charges:</td>
                            <td class="text-end text-primary">৳{{ number_format($opd->grand_total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @elseif($activeTab == 'payments')
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Payments History</h6>
                <button wire:click="$set('showPaymentModal', true)" class="btn btn-success btn-sm px-3 shadow-none">+ Record Payment</button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle small border">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Payment Method</th>
                            <th>Reference/Cheque</th>
                            <th class="text-end">Paid Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($opd->payments as $p)
                        <tr>
                            <td>{{ $p->created_at->format('d M, Y h:i A') }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $p->method->name }}</span></td>
                            <td>{{ $p->cheque_no ?? 'Direct/Cash' }}</td>
                            <td class="text-end fw-bold text-success">৳{{ number_format($p->paid_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No payments recorded yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr class="fw-bold">
                            <td colspan="3" class="text-end">Total Collected:</td>
                            <td class="text-end text-success">৳{{ number_format($opd->total_paid, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
        </div>
    </div>

    <!-- MODAL: ADD CHARGE -->
    @if($showChargeModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <form wire:submit.prevent="addCharge" class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add Service Charge</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showChargeModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Category</label>
                        <select wire:model.live="charge_category_id" class="form-select">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Charge Item</label>
                        <select wire:model.live="charge_id" class="form-select @error('charge_id') is-invalid @enderror">
                            <option value="">Select Item</option>
                            @foreach($charges as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Applied Rate</label>
                            <input type="number" wire:model.live="applied_charge" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Tax ({{ $tax_percentage }}%)</label>
                            <input type="text" value="৳{{ number_format($tax_amount, 2) }}" class="form-control bg-light" readonly>
                        </div>
                    </div>
                    <div class="mt-3 p-3 bg-light rounded text-center">
                        <span class="text-muted small text-uppercase">Total Charge Net</span>
                        <h4 class="mb-0 fw-bold text-primary">৳{{ number_format($net_amount, 2) }}</h4>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100">Save Charge</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- MODAL: ADD PAYMENT -->
    @if($showPaymentModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <form wire:submit.prevent="addPayment" class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Record Payment</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showPaymentModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Collection Amount</label>
                        <input type="number" wire:model="paid_amount" class="form-control form-control-lg border-success fw-bold text-success">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Payment Method</label>
                        <select wire:model.live="payment_method_id" class="form-select">
                            @foreach($paymentMethods as $pm) <option value="{{ $pm->id }}">{{ $pm->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Reference / Cheque No</label>
                        <input type="text" wire:model="cheque_no" class="form-control" placeholder="Optional">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-success w-100">Confirm Payment</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>