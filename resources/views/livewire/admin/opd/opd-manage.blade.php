<div class="card shadow-lg border-0">
    <!-- Header Search -->
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center flex-grow-1">
            <div class="position-relative col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" wire:model.live.debounce.300ms="patient_search" class="form-control border-start-0" placeholder="Search Patient (Name, Phone, MRN)...">
                </div>
                @if(!empty($patient_results))
                <div class="list-group position-absolute w-100 shadow-lg mt-1" style="z-index: 1050;">
                    @foreach($patient_results as $p)
                    <button type="button" wire:click="selectPatient({{ $p->id }})" class="list-group-item list-group-item-action py-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $p->user->name }}</strong>
                            <small class="text-muted">{{ $p->mrn_number }}</small>
                        </div>
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
            <button type="button" wire:click="$set('showPatientModal', true)" class="btn btn-primary ms-3 shadow-sm">
                <i class="bi bi-person-plus-fill me-1"></i> New Patient
            </button>
        </div>
    </div>

    <form wire:submit.prevent="save" class="card-body p-0">
        <div class="row g-0">
            <!-- Left Panel -->
            <div class="col-lg-8 border-end p-4">
                @if($selected_patient_data)
                <div class="bg-light p-4 rounded mb-4 border-start border-primary border-4 shadow-sm">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h4 class="mb-1 fw-bold text-primary">{{ $selected_patient_data->user->name }}</h4>
                            <p class="mb-0 text-muted">MRN: <strong class="text-dark">{{ $selected_patient_data->mrn_number }}</strong> | Guardian: <strong class="text-dark">{{ $selected_patient_data->guardian_name }}</strong></p>
                            <span class="badge bg-soft-info text-info mt-2">TPA: {{ $selected_patient_data->tpa->name ?? 'Self Pay' }}</span>
                        </div>
                        <div class="col-md-3 text-end">
                            <img src="{{ asset('storage/'.$selected_patient_data->photo) }}" class="rounded shadow-sm" width="80" onerror="this.src='https://ui-avatars.com/api/?name={{ $selected_patient_data->user->name }}'">
                        </div>
                    </div>
                </div>
                @endif

                <!-- Symptom Adder Section -->
                <h6 class="fw-bold mb-3 text-uppercase text-muted" style="font-size: 0.8rem;">Clinical Symptoms</h6>
                <div class="row g-3 mb-4 p-3 bg-white border rounded">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Category</label>
                        <select wire:model.live="temp_type_id" class="form-select">
                            <option value="">Select Category</option>
                            @foreach($symptomTypes as $st) <option value="{{ $st->id }}">{{ $st->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Specific Symptom</label>
                        <select wire:model="temp_title_id" class="form-select" @if(!$temp_type_id) disabled @endif>
                            <option value="">Select Symptom</option>
                            @foreach($symptomTitles as $title) <option value="{{ $title->id }}">{{ $title->title }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" wire:click="addSymptom" class="btn btn-dark w-100">Add</button>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive border rounded mt-2">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Category</th>
                                        <th>Symptom</th>
                                        <th class="text-end pe-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($added_symptoms as $idx => $s)
                                    <tr>
                                        <td class="ps-3">{{ $s['type_name'] }}</td>
                                        <td class="fw-bold">{{ $s['title_name'] }}</td>
                                        <td class="text-end pe-3"><button type="button" wire:click="removeSymptom({{ $idx }})" class="btn btn-link text-danger p-0"><i class="bi bi-x-circle-fill"></i></button></td>
                                    </tr>
                                    @endforeach
                                    @if(empty($added_symptoms))<tr>
                                        <td colspan="3" class="text-center py-3 text-muted">No symptoms added.</td>
                                    </tr>@endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-12"><label class="form-label fw-bold">Allergies</label><input type="text" wire:model="known_allergies" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label fw-bold">Symptom Description</label><textarea wire:model="symptoms_description" class="form-control" rows="3"></textarea></div>
                    <div class="col-md-6"><label class="form-label fw-bold">Clinical Note</label><textarea wire:model="note" class="form-control" rows="3"></textarea></div>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="col-lg-4 p-4 bg-light">
                <div class="row g-3">
                    <div class="col-12"><label class="form-label fw-bold">Admission Date & Time</label><input type="datetime-local" wire:model="appointment_date" class="form-control"></div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Consultant Doctor *</label>
                        <select wire:model="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror">
                            <option value="">Select</option>
                            @foreach($doctors as $d) <option value="{{ $d->id }}">{{ $d->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <hr class="my-3">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Charge Item *</label>
                        <select wire:model.live="charge_id" class="form-select @error('charge_id') is-invalid @enderror">
                            <option value="">Select Service</option>
                            @foreach($charges as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-6"><label class="form-label fw-bold small text-muted">Applied Charge</label><input type="number" wire:model.live="applied_charge" class="form-control fw-bold text-primary"></div>
                    <div class="col-6"><label class="form-label fw-bold small text-muted">Discount (%)</label><input type="number" wire:model.live="discount_percentage" class="form-control"></div>

                    <div class="col-12 bg-white p-3 border border-primary rounded shadow-sm my-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary">NET PAYABLE:</span>
                        <span class="h4 mb-0 fw-bold">৳{{ number_format($net_amount, 2) }}</span>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Payment Method</label>
                        <select wire:model="payment_method_id" class="form-select">@foreach($paymentMethods as $pm) <option value="{{ $pm->id }}">{{ $pm->name }}</option> @endforeach</select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold text-success">Paid Amount *</label>
                        <input type="number" wire:model="paid_amount" class="form-control border-success fw-bold text-success" style="font-size: 1.25rem;">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-3 mt-4 fw-bold shadow">CONFIRM OPD ADMISSION</button>
            </div>
        </div>
    </form>

    @if($showPatientModal)
    <livewire:common.quick-patient-modal />
    @endif
</div>