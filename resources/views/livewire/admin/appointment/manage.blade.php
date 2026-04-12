<div class="">
    <form wire:submit.prevent="save">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-calendar-check me-2"></i>Appointment Booking</h5>
                <button type="submit" class="btn btn-primary px-4 shadow-sm">Confirm Appointment</button>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">
                    <!-- Patient Selection -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">Patient <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <div class="input-group">
                                <input type="text" wire:model.live.debounce.300ms="patient_search" class="form-control" placeholder="Search Patient...">
                                <button type="button" wire:click="$set('showPatientModal', true)" class="btn btn-outline-primary"><i class="bi bi-plus-lg"></i> New</button>
                            </div>
                            @if(!empty($patient_results))
                            <div class="list-group position-absolute w-100 shadow-lg mt-1" style="z-index: 1050;">
                                @foreach($patient_results as $p)
                                <button type="button" wire:click="selectPatient({{ $p->id }}, '{{ $p->user->name }}')" class="list-group-item list-group-item-action py-2">
                                    <div class="fw-bold">{{ $p->user->name }}</div>
                                    <small class="text-muted">{{ $p->mrn_number }} | {{ $p->user->phone }}</small>
                                </button>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @error('patient_id') <small class="text-danger">Please select a patient</small> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold small">Doctor *</label>
                        <select wire:model.live="doctor_id" class="form-select">
                            <option value="">Select Doctor</option>
                            @foreach($doctors as $d) <option value="{{ $d->id }}">{{ $d->name }}</option> @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold small">Date *</label>
                        <input type="date" wire:model="date" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold small">Shift *</label>
                        <select wire:model="global_shift_id" class="form-select">
                            <option value="">Select</option>
                            @foreach($shifts as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                        </select>
                    </div>

                    <!-- Financial Row -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Standard Fees</label>
                        <div class="input-group">
                            <span class="input-group-text">৳</span>
                            <input type="text" value="{{ number_format($hospital_fees, 2) }}" class="form-control bg-light" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold small">Discount (%)</label>
                        <input type="number" wire:model.live="discount_percentage" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-primary">Net Amount</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white border-primary">৳</span>
                            <input type="text" value="{{ number_format($net_amount, 2) }}" class="form-control border-primary fw-bold" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">Payment Method *</label>
                        <select wire:model.live="payment_method_id" class="form-select">
                            <option value="">Select Method</option>
                            @foreach($paymentMethods as $pm) <option value="{{ $pm->id }}">{{ $pm->name }}</option> @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold small">Message / Symptoms</label>
                        <textarea wire:model="message" class="form-control" rows="2" placeholder="Describe symptoms or reasons..."></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- QUICK ADD PATIENT MODAL (Detailed Form) -->
    @if($showPatientModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary p-3">
                    <h5 class="modal-title fw-bold text-white">Quick Patient Registration</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showPatientModal', false)"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3">
                        <!-- Left Section: Basic Info -->
                        <div class="col-md-8 border-end">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Full Name *</label>
                                    <input type="text" wire:model="name" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Phone Number *</label>
                                    <input type="text" wire:model="phone" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Gender *</label>
                                    <select wire:model="gender_val" class="form-select">
                                        <option value="">Select</option>
                                        @foreach($genders as $g) <option value="{{ $g->value }}">{{ $g->value }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Date of Birth</label>
                                    <input type="date" wire:model.live="date_of_birth" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Age (YY-MM-DD)</label>
                                    <div class="input-group">
                                        <input type="number" wire:model.live="age_year" class="form-control" placeholder="YY">
                                        <input type="number" wire:model.live="age_month" class="form-control" placeholder="MM">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Guardian Name</label>
                                    <input type="text" wire:model="guardian_name" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Marital Status</label>
                                    <select wire:model="marital_status" class="form-select">
                                        <option value="">Select</option>
                                        @foreach($marital_statuses as $ms) <option value="{{ $ms->value }}">{{ $ms->value }}</option> @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Right Section -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Address</label>
                                <textarea wire:model="address" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Blood Group</label>
                                <select wire:model="blood_group" class="form-select">
                                    <option value="">Select</option>
                                    @foreach($blood_groups as $bg) <option value="{{ $bg->value }}">{{ $bg->value }}</option> @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" wire:click="$set('showPatientModal', false)">Cancel</button>
                    <button type="button" wire:click="saveQuickPatient" class="btn btn-primary px-4 shadow-sm">
                        <span wire:loading wire:target="saveQuickPatient" class="spinner-border spinner-border-sm me-1"></span>
                        Register & Select Patient
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>