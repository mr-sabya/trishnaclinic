<div class="container-fluid py-4">
    <form wire:submit.prevent="save">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-calendar-plus me-2"></i>Appointment Booking</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.appointment.index') }}" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="bi bi-check-lg me-1"></i> Confirm Appointment
                    </button>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">
                    <!-- Patient Selection -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-uppercase text-muted">Patient <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                                <input type="text" wire:model.live.debounce.300ms="patient_search" class="form-control" placeholder="Search by Name/Phone/MRN...">
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

                    <!-- Doctor Selection -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-uppercase text-muted">Doctor <span class="text-danger">*</span></label>
                        <select wire:model.live="doctor_id" class="form-select">
                            <option value="">Select Doctor</option>
                            @foreach($doctors as $d) <option value="{{ $d->id }}">{{ $d->name }}</option> @endforeach
                        </select>
                        @error('doctor_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Date and Shift -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold small text-uppercase text-muted">Date *</label>
                        <input type="date" wire:model="date" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold small text-uppercase text-muted">Shift *</label>
                        <select wire:model.live="global_shift_id" class="form-select">
                            <option value="">All Shifts</option>
                            @foreach($shifts as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                        </select>
                    </div>

                    <!-- DR SCHEDULE SECTION -->
                    @if($doctor_id)
                    <div class="col-12 mt-2">
                        <div class="p-3 border rounded bg-light">
                            <h6 class="fw-bold mb-3 small text-primary text-uppercase"><i class="bi bi-clock me-2"></i>Available Slots / Schedule</h6>
                            <div class="row g-3">
                                @forelse($doctor_schedules as $sch)
                                <div class="col-md-4">
                                    <div class="card h-100 border-2 {{ $doctor_schedule_id == $sch->id ? 'border-primary shadow-sm' : 'border-transparent' }}"
                                        style="cursor: pointer;" wire:click="$set('doctor_schedule_id', {{ $sch->id }})">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <span class="badge bg-soft-primary text-primary border border-primary mb-2">{{ $sch->shift->name }}</span>
                                                @if($doctor_schedule_id == $sch->id)
                                                <i class="bi bi-check-circle-fill text-primary"></i>
                                                @endif
                                            </div>
                                            <div class="fw-bold">{{ \Carbon\Carbon::parse($sch->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($sch->end_time)->format('h:i A') }}</div>
                                            <div class="text-muted small mt-1">
                                                @foreach($sch->available_days as $day)
                                                <span class="badge bg-secondary opacity-75" style="font-size: 0.65rem;">{{ substr($day->value, 0, 3) }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 text-center py-3 text-muted">
                                    <i class="bi bi-calendar-x me-2"></i> No active schedules found for the selected criteria.
                                </div>
                                @endforelse
                            </div>
                            @error('doctor_schedule_id') <div class="text-danger small mt-2">Please select a schedule slot</div> @enderror
                        </div>
                    </div>
                    @endif

                    <!-- Financial Details -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Consultation Fees</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">৳</span>
                            <input type="text" value="{{ number_format($hospital_fees + $doctor_fees, 2) }}" class="form-control bg-light" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold small text-uppercase text-muted">Discount (%)</label>
                        <input type="number" wire:model.live="discount_percentage" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-uppercase text-primary">Net Payable</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white border-primary">৳</span>
                            <input type="text" value="{{ number_format($net_amount, 2) }}" class="form-control border-primary fw-bold text-primary" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-uppercase text-muted">Payment Method *</label>
                        <select wire:model.live="payment_method_id" class="form-select">
                            <option value="">Select Method</option>
                            @foreach($paymentMethods as $pm) <option value="{{ $pm->id }}">{{ $pm->name }}</option> @endforeach
                        </select>
                        @error('payment_method_id') <small class="text-danger">Required</small> @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold small text-uppercase text-muted">Message / Reason for Appointment</label>
                        <textarea wire:model="message" class="form-control" rows="2" placeholder="Briefly describe symptoms or purpose..."></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- QUICK ADD PATIENT MODAL -->
    @if($showPatientModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary p-3">
                    <h5 class="modal-title fw-bold text-white"><i class="bi bi-person-plus me-2"></i>Quick Patient Registration</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showPatientModal', false)"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3">
                        <div class="col-md-8 border-end">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Full Name *</label>
                                    <input type="text" wire:model="name" class="form-control">
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Phone Number *</label>
                                    <input type="text" wire:model="phone" class="form-control">
                                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
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
                                    <label class="form-label small fw-bold">Age (YY-MM)</label>
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
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Blood Group</label>
                                <select wire:model="blood_group" class="form-select">
                                    <option value="">Select</option>
                                    @foreach($blood_groups as $bg) <option value="{{ $bg->value }}">{{ $bg->value }}</option> @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Address</label>
                                <textarea wire:model="address" class="form-control" rows="4"></textarea>
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

    <style>
        .bg-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .border-transparent {
            border-color: transparent;
        }
    </style>
</div>