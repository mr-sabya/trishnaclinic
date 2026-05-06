<div class="container-fluid py-4">
    @if (session()->has('success'))
    <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <!-- Detailed Header -->
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
                    <a href="{{ route('admin.opd.print-blank', $opd->id) }}" target="_blank" class="btn btn-outline-dark shadow-sm fw-bold">
                        <i class="bi bi-printer-fill me-2"></i> Print Blank Prescription
                    </a>
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 {{ $opd->balance > 0 ? 'bg-danger' : 'bg-success' }} text-white h-100">
                <small class="opacity-75">Balance Due</small>
                <h3 class="mb-0 fw-bold">৳ {{ number_format($opd->balance, 2) }}</h3>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation with COUNTS -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0 pt-3">
            <ul class="nav nav-tabs card-header-tabs border-bottom-0">
                @php
                $tabs = [
                'overview' => 'Overview',
                'symptoms' => 'Symptoms ('.$opd->symptoms->count().')',
                'pathology' => 'Pathology ('.$opd->pathologyTests->count().')',
                'radiology' => 'Radiology ('.$opd->radiologyTests->count().')',
                'charges' => 'Charges ('.$opd->charges->count().')',
                'payments' => 'Payments ('.$opd->payments->count().')'
                ];
                @endphp
                @foreach($tabs as $key => $label)
                <li class="nav-item">
                    <button wire:click="$set('activeTab', '{{ $key }}')" class="nav-link border-0 {{ $activeTab == $key ? 'active fw-bold text-primary border-bottom border-primary' : 'text-muted' }}">{{ $label }}</button>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="card-body">
            <!-- 1. OVERVIEW -->
            @if($activeTab == 'overview')
            <div class="row g-4 small">
                <div class="col-md-6 border-end">
                    <h6 class="fw-bold text-muted small text-uppercase mb-3">Clinical Info</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="40%">Consultant:</td>
                            <td class="fw-bold">{{ $opd->doctor->name }}</td>
                        </tr>
                        <tr>
                            <td>Admission Date:</td>
                            <td>{{ $opd->appointment_date->format('d M Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <td>Allergies:</td>
                            <td class="text-danger fw-bold">{{ $opd->known_allergies ?? 'None' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-muted small text-uppercase mb-3">Financial Info</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td>Initial Admission:</td>
                            <td class="text-end">৳{{ number_format($opd->net_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Additional Charges:</td>
                            <td class="text-end">৳{{ number_format($opd->charges->sum('net_amount'), 2) }}</td>
                        </tr>
                        <tr class="border-top fw-bold">
                            <td>Grand Total:</td>
                            <td class="text-end">৳{{ number_format($opd->grand_total, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- 2. SYMPTOMS (RE-ADDED) -->
            @elseif($activeTab == 'symptoms')
            <div class="d-flex justify-content-between mb-3">
                <h6 class="fw-bold mb-0">Clinical Symptoms</h6><button wire:click="$set('showSymptomModal', true)" class="btn btn-dark btn-sm shadow-sm">+ Add Symptom</button>
            </div>
            <table class="table table-hover small border">
                <thead class="table-light">
                    <tr>
                        <th>Category</th>
                        <th>Symptom Title</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($opd->symptoms as $s) <tr>
                        <td>{{ $s->type->name }}</td>
                        <td class="fw-bold">{{ $s->title->title }}</td>
                    </tr>
                    @empty <tr>
                        <td colspan="2" class="text-center py-4 text-muted">No symptoms recorded.</td>
                    </tr> @endforelse
                </tbody>
            </table>

            <!-- 3. INVESTIGATIONS (PATH/RAD) -->
            @elseif($activeTab == 'pathology' || $activeTab == 'radiology')
            @php $type = $activeTab; $tests = ($type == 'pathology') ? $opd->pathologyTests : $opd->radiologyTests; @endphp
            <div class="d-flex justify-content-between mb-3">
                <h6 class="fw-bold mb-0 text-capitalize">{{ $type }} Investigations</h6>
                <button wire:click="openTestModal('{{ $type }}')" class="btn btn-info btn-sm text-white shadow-sm">+ Add {{ ucfirst($type) }} Test</button>
            </div>
            <table class="table table-hover small border">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Test Name</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tests as $t)
                    <tr>
                        <td>{{ $t->test_date->format('d M, Y') }}</td>
                        <td class="fw-bold">{{ $t->test->test_name }}</td>
                        <td><span class="badge @if($t->status == 'Completed') bg-success @elseif($t->status == 'Cancelled') bg-danger @else bg-secondary @endif">{{ $t->status }}</span></td>
                        <td class="text-end"><button wire:click="editTestStatus('{{ $type }}', {{ $t->id }})" class="btn btn-link p-0"><i class="bi bi-pencil-square"></i></button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- 4. CHARGES (FIXED TOTAL) -->
            @elseif($activeTab == 'charges')
            <div class="d-flex justify-content-between mb-3">
                <h6 class="fw-bold mb-0">Billing Items</h6><button wire:click="$set('showChargeModal', true)" class="btn btn-primary btn-sm shadow-sm">+ Add Service Charge</button>
            </div>
            <table class="table table-hover small border">
                <thead class="table-light">
                    <tr>
                        <th>Charge Name</th>
                        <th class="text-end">Net Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opd->charges as $c) <tr>
                        <td>{{ $c->chargeMaster->name }}</td>
                        <td class="text-end fw-bold">৳{{ number_format($c->net_amount, 2) }}</td>
                    </tr> @endforeach
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td class="text-end">Total Additional Charges:</td>
                        <td class="text-end text-primary">৳{{ number_format($opd->charges->sum('net_amount'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <!-- 5. PAYMENTS -->
            @elseif($activeTab == 'payments')
            <div class="d-flex justify-content-between mb-3">
                <h6 class="fw-bold mb-0">Payments History</h6><button wire:click="$set('showPaymentModal', true)" class="btn btn-success btn-sm shadow-sm">+ Record Payment</button>
            </div>
            <table class="table table-hover small border">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opd->payments as $p) <tr>
                        <td>{{ $p->created_at->format('d M, Y') }}</td>
                        <td>{{ $p->method->name }}</td>
                        <td>{{ $p->cheque_no ?? '-' }}</td>
                        <td class="text-end text-success fw-bold">৳{{ number_format($p->paid_amount, 2) }}</td>
                    </tr> @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    <!-- MODAL: SYMPTOM (WITH QUICK ADD) -->
    @if($showSymptomModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content shadow border-0">
                <div class="modal-header bg-dark text-white p-3">
                    <h5 class="modal-title text-white">Patient Symptoms</h5><button type="button" class="btn-close btn-close-white" wire:click="$set('showSymptomModal', false)"></button>
                </div>
                <div class="modal-body small">
                    <label class="fw-bold">Category</label>
                    <select wire:model.live="symptom_type_id" class="form-select mb-3">
                        <option value="">Select Category</option>@foreach($symptomTypes as $st) <option value="{{ $st->id }}">{{ $st->name }}</option> @endforeach
                    </select>
                    <label class="fw-bold">Select Existing Title</label>
                    <select wire:model="symptom_title_id" class="form-select mb-3" @if(!$symptom_type_id) disabled @endif>
                        <option value="">Select Title</option>@foreach($symptomTitles as $st) <option value="{{ $st->id }}">{{ $st->title }}</option> @endforeach
                    </select>
                    <div class="bg-light p-2 rounded border">
                        <label class="text-muted fw-bold">Quick add new master option:</label>
                        <div class="input-group input-group-sm mt-1">
                            <input type="text" wire:model="new_symptom_title_name" class="form-control" placeholder="New master title..." @if(!$symptom_type_id) disabled @endif>
                            <button wire:click="createNewSymptomOption" class="btn btn-outline-dark" @if(!$symptom_type_id) disabled @endif>Add Master</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button wire:click="addSymptom" class="btn btn-dark w-100" @if(!$symptom_title_id) disabled @endif>Save to Patient</button></div>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL: INVESTIGATION STATUS -->
    @if($showStatusModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-sm">
            <form wire:submit.prevent="updateTestStatus" class="modal-content shadow border-0">
                <div class="modal-header bg-dark p-3 align-items-center">
                    <h6 class="modal-title text-white">Update Status</h6>
                    <button type="button" class="btn-close btn-close-white ms-auto" wire:click="$set('showStatusModal', false)"></button>
                </div>
                <div class="modal-body text-center">
                    <select wire:model="newStatus" class="form-select border-2 mb-3">@foreach($statusOptions as $opt) <option value="{{ $opt }}">{{ $opt }}</option> @endforeach</select>
                    <button type="submit" class="btn btn-primary w-100">Update</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- MODALS: INVESTIGATION ADD -->
    @foreach(['Pathology', 'Radiology'] as $mType)
    @php $visible = "show{$mType}Modal"; $catId = strtolower($mType)."_category_id"; $testId = strtolower($mType)."_test_id"; $cats = ($mType == 'Pathology' ? $pathologyCategories : $radiologyCategories); $tests = ($mType == 'Pathology' ? $pathologyTests : $radiologyTests); @endphp
    @if($$visible)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <form wire:submit.prevent="add{{ $mType }}Test" class="modal-content shadow border-0">
                <div class="modal-header bg-info p-3">
                    <h5 class=" text-white">Add {{ $mType }} Test</h5><button type="button" class="btn-close btn-close-white" wire:click="$set('{{ $visible }}', false)"></button>
                </div>
                <div class="modal-body">
                    <label class="fw-bold small">Category</label>
                    <select wire:model.live="{{ $catId }}" class="form-select mb-3">
                        <option value="">Select Category</option>@foreach($cats as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                    </select>
                    <label class="fw-bold small">Test</label>
                    <select wire:model="{{ $testId }}" class="form-select">
                        <option value="">Select Test</option>@foreach($tests as $t) <option value="{{ $t->id }}">{{ $t->test_name }} (৳{{ $t->total_amount }})</option> @endforeach
                    </select>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-info text-white w-100">Add & Bill Investigation</button></div>
            </form>
        </div>
    </div>
    @endif
    @endforeach

    <!-- MODAL: ADD CHARGE -->
    @if($showChargeModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <form wire:submit.prevent="addCharge" class="modal-content shadow border-0">
                <div class="modal-header bg-primary p-3">
                    <h5 class="modal-title text-white">Add Service Charge</h5><button type="button" class="btn-close btn-close-white" wire:click="$set('showChargeModal', false)"></button>
                </div>
                <div class="modal-body">
                    <label class="small fw-bold">Service Category</label>
                    <select wire:model.live="charge_category_id" class="form-select mb-3">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <label class="small fw-bold">Service Item</label>
                    <select wire:model.live="charge_id" class="form-select mb-3">
                        <option value="">Select Service</option>
                        @foreach($charges as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <div class="row g-2 mb-3">
                        <div class="col-6"><label class="small fw-bold">Applied Rate</label><input type="number" wire:model.live="applied_charge" class="form-control"></div>
                        <div class="col-6"><label class="small fw-bold">Tax</label><input type="text" value="৳{{ number_format($tax_amount, 2) }}" class="form-control bg-light" readonly></div>
                    </div>
                    <div class="p-3 bg-light rounded text-center"><small class="text-muted text-uppercase">Total Net</small>
                        <h4 class="fw-bold text-primary mb-0">৳{{ number_format($net_amount, 2) }}</h4>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3"><button type="submit" class="btn btn-primary w-100 fw-bold">Save Charge</button></div>
            </form>
        </div>
    </div>
    @endif

    <!-- MODAL: RECORD PAYMENT -->
    @if($showPaymentModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-sm">
            <form wire:submit.prevent="addPayment" class="modal-content shadow border-0">
                <div class="modal-header bg-success p-3">
                    <h6 class="modal-title text-white">Record Payment</h6><button type="button" class="btn-close btn-close-white" wire:click="$set('showPaymentModal', false)"></button>
                </div>
                <div class="modal-body">
                    <label class="small fw-bold">Amount Paid</label>
                    <input type="number" wire:model="paid_amount" class="form-control form-control-lg text-success fw-bold text-center border-success mb-3">
                    <label class="small fw-bold">Payment Method</label>
                    <select wire:model.live="payment_method_id" class="form-select mb-3">
                        <option value="">Select Payment Method</option>
                        @foreach($paymentMethods as $pm)
                        <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                        @endforeach
                    </select>
                    <label class="small fw-bold text-muted">Cheque / Reference No</label>
                    <input type="text" wire:model="cheque_no" class="form-control">
                </div>
                <div class="modal-footer border-0 p-3"><button type="submit" class="btn btn-success w-100 fw-bold">Confirm Collection</button></div>
            </form>
        </div>
    </div>
    @endif
</div>