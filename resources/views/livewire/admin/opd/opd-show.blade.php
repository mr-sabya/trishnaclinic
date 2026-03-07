<div class="container-fluid">
    <!-- Header Summary -->
    <div class="row mb-4 g-3">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <img src="{{ $opd->patient->photo ? asset('storage/'.$opd->patient->photo) : 'https://ui-avatars.com/api/?name='.$opd->patient->user->name }}" class="rounded-circle me-3 border" width="70">
                    <div>
                        <h4 class="mb-0 fw-bold">{{ $opd->patient->user->name }}</h4>
                        <span class="badge bg-soft-primary text-primary">{{ $opd->opd_number }}</span>
                        <span class="text-muted small ms-2">MRN: {{ $opd->patient->mrn_number }} | {{ $opd->patient->gender->value }} | {{ $opd->patient->getDetailedAgeAttribute()['y'] }} Years</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-white-50">Balance Due</small>
                        <h3 class="mb-0 fw-bold">৳ {{ number_format($opd->balance, 2) }}</h3>
                    </div>
                    <i class="bi bi-wallet2 fs-1 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Tabs -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0 pt-3">
            <ul class="nav nav-tabs-custom nav-success card-header-tabs border-bottom-0">
                <li class="nav-item"><button wire:click="$set('activeTab', 'overview')" class="nav-link {{ $activeTab == 'overview' ? 'active' : '' }}">Overview</button></li>
                <li class="nav-item"><button wire:click="$set('activeTab', 'charges')" class="nav-link {{ $activeTab == 'charges' ? 'active' : '' }}">Charges ({{ $opd->charges->count() }})</button></li>
                <li class="nav-item"><button wire:click="$set('activeTab', 'payments')" class="nav-link {{ $activeTab == 'payments' ? 'active' : '' }}">Payments</button></li>
            </ul>
        </div>
        <div class="card-body">
            @if($activeTab == 'overview')
            <div class="row">
                <div class="col-md-6 border-end">
                    <h6 class="fw-bold border-bottom pb-2">Clinical Details</h6>
                    <table class="table table-sm table-borderless small">
                        <tr>
                            <td class="text-muted">Doctor:</td>
                            <td class="fw-bold">{{ $opd->doctor->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Appt Date:</td>
                            <td>{{ $opd->appointment_date->format('d M Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Case:</td>
                            <td><span class="badge bg-light text-dark">{{ $opd->case_type }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Known Allergies:</td>
                            <td class="text-danger fw-bold">{{ $opd->known_allergies ?? 'None' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold border-bottom pb-2">Action Center</h6>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-success btn-sm text-start" onclick="window.print()"><i class="bi bi-printer me-2"></i> Print Bill Summary</button>
                        <a href="#" class="btn btn-outline-primary btn-sm text-start"><i class="bi bi-file-earmark-medical me-2"></i> Generate Prescription</a>
                    </div>
                </div>
            </div>

            @elseif($activeTab == 'charges')
            <div class="d-flex justify-content-between mb-3">
                <h6 class="fw-bold">Billing Line Items</h6>
                <button wire:click="$set('showChargeModal', true)" class="btn btn-primary btn-sm">+ Add Charge</button>
            </div>
            <table class="table table-sm align-middle small">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Charge Name</th>
                        <th>Rate</th>
                        <th>Tax</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opd->charges as $c)
                    <tr>
                        <td>{{ $c->created_at->format('d M') }}</td>
                        <td>{{ $c->chargeMaster->name }}</td>
                        <td>৳{{ number_format($c->applied_charge, 2) }}</td>
                        <td>{{ $c->tax_percentage }}%</td>
                        <td class="text-end fw-bold">৳{{ number_format($c->net_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-light fw-bold">
                        <td colspan="4" class="text-end">Grand Total:</td>
                        <td class="text-end text-primary">৳{{ number_format($opd->grand_total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            @elseif($activeTab == 'payments')
            <div class="d-flex justify-content-between mb-3">
                <h6 class="fw-bold">Payment Transactions</h6>
                <button wire:click="$set('showPaymentModal', true)" class="btn btn-success btn-sm">+ Add Payment</button>
            </div>
            <table class="table table-sm small">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Ref/Cheque</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opd->payments as $p)
                    <tr>
                        <td>{{ $p->created_at->format('d M Y') }}</td>
                        <td>{{ $p->method->name }}</td>
                        <td>{{ $p->cheque_no ?? '-' }}</td>
                        <td class="text-end fw-bold text-success">৳{{ number_format($p->paid_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    <!-- MODAL: ADD CHARGE -->
    @if($showChargeModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <form wire:submit.prevent="addCharge" class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5>Add Service Charge</h5><button type="button" class="btn-close btn-close-white" wire:click="$set('showChargeModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Category</label>
                        <select wire:model.live="charge_category_id" class="form-select form-select-sm">
                            <option value="">Select</option>
                            @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Charge *</label>
                        <select wire:model.live="charge_id" class="form-select form-select-sm">
                            <option value="">Select</option>
                            @foreach($charges as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3"><label class="form-label small fw-bold">Rate</label><input type="number" wire:model.live="applied_charge" class="form-control form-control-sm"></div>
                        <div class="col-6 mb-3"><label class="form-label small fw-bold text-primary">Total Net</label><input type="text" value="৳{{ number_format($net_amount, 2) }}" class="form-control form-control-sm bg-light" readonly></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary btn-sm w-100">Save Charge</button></div>
            </form>
        </div>
    </div>
    @endif

    <!-- MODAL: ADD PAYMENT -->
    @if($showPaymentModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <form wire:submit.prevent="addPayment" class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5>Record Payment</h5><button type="button" class="btn-close btn-close-white" wire:click="$set('showPaymentModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label small fw-bold">Amount To Pay</label><input type="number" wire:model="paid_amount" class="form-control form-control-sm border-success fw-bold"></div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Method</label>
                        <select wire:model.live="payment_method_id" class="form-select form-select-sm">
                            @foreach($paymentMethods as $pm) <option value="{{ $pm->id }}">{{ $pm->name }}</option> @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-success btn-sm w-100">Confirm Payment</button></div>
            </form>
        </div>
    </div>
    @endif
</div>