<div class="card shadow-lg border-0">
    @if (session()->has('error'))
    <div class="alert alert-danger m-3">{{ session('error') }}</div>
    @endif

    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center flex-grow-1">
            <div class="position-relative col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" wire:model.live.debounce.300ms="patient_search"
                        class="form-control border-start-0 shadow-none @error('patient_id') is-invalid @enderror"
                        placeholder="Search Patient (Name/MRN/Phone)...">
                </div>
                @if(!empty($patient_results))
                <div class="list-group position-absolute w-100 shadow-lg mt-1" style="z-index: 1050;">
                    @foreach($patient_results as $p)
                    <button type="button" wire:click="selectPatient({{ $p->id }})" class="list-group-item list-group-item-action py-3">
                        <div class="fw-bold">{{ $p->user->name }}</div>
                        <small class="text-muted">MRN: {{ $p->mrn_number }} | Phone: {{ $p->user->phone }}</small>
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
            <button type="button" wire:click="$set('showPatientModal', true)" class="btn btn-primary ms-3 shadow-none">+ New Patient</button>
        </div>
    </div>

    <form wire:submit.prevent="save" class="card-body p-0">
        <div class="row g-0">
            <!-- Left Panel -->
            <div class="col-lg-8 border-end p-4">
                @if($selected_patient_data)
                <div class="d-flex align-items-center bg-light p-3 rounded mb-4 border-start border-primary border-4 shadow-sm position-relative">

                    <!-- 1. Image First -->
                    <div class="me-3">
                        <img src="{{ asset('storage/'.$selected_patient_data->photo) }}"
                            class="rounded shadow-sm border"
                            width="80"
                            height="80"
                            style="object-fit: cover;"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($selected_patient_data->user->name) }}&background=0D6EFD&color=fff'">
                    </div>

                    <!-- 2. Patient Details -->
                    <div class="flex-grow-1">
                        <h4 class="mb-1 fw-bold text-primary">{{ $selected_patient_data->user->name }}</h4>
                        <p class="mb-0 text-muted">
                            MRN: <strong>{{ $selected_patient_data->mrn_number }}</strong> |
                            Gender: <strong>{{ $selected_patient_data->gender->value }}</strong> |
                            Age: <strong>{{ $selected_patient_data->age }}</strong>
                        </p>
                    </div>

                    <!-- 3. Change Button (on the far right) -->
                    <div class="ms-auto align-self-start">
                        <button type="button"
                            wire:click="clearPatient"
                            class="btn btn-sm btn-outline-danger shadow-none border-0"
                            title="Remove and search again">
                            <i class="bi bi-x-circle-fill me-1"></i> Change Patient
                        </button>
                    </div>
                </div>
                @endif

                <h6 class="fw-bold mb-3 text-uppercase text-muted small">Clinical Symptoms (Optional)</h6>
                <div class="row g-3 mb-4 p-4 bg-white border rounded shadow-sm">
                    <div class="col-md-5">
                        <label class="small fw-bold">Category</label>
                        <select wire:model.live="temp_type_id" class="form-select shadow-none">
                            <option value="">Select Category</option>
                            @foreach($symptomTypes as $st) <option value="{{ $st->id }}">{{ $st->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="small fw-bold">Symptom</label>
                        <select wire:model="temp_title_id" class="form-select shadow-none" @if(!$temp_type_id) disabled @endif>
                            <option value="">Select Symptom</option>
                            @foreach($symptomTitles as $title) <option value="{{ $title->id }}">{{ $title->title }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small">&nbsp;</label>
                        <button type="button" wire:click="addSymptom" class="btn btn-dark w-100 shadow-none">Add</button>
                    </div>

                    <div class="col-12 mt-3">
                        <table class="table table-sm border">
                            <thead class="table-light">
                                <tr>
                                    <th>Category</th>
                                    <th>Symptom</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($added_symptoms as $idx => $s)
                                <tr>
                                    <td>{{ $s['type_name'] }}</td>
                                    <td class="fw-bold">{{ $s['title_name'] }}</td>
                                    <td class="text-end"><button type="button" wire:click="removeSymptom({{ $idx }})" class="btn btn-link text-danger p-0 shadow-none"><i class="bi bi-x-circle-fill"></i></button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <h6 class="fw-bold mb-3 text-uppercase text-muted small">Service Charges (Optional)</h6>
                <div class="row g-3 p-4 bg-white border rounded shadow-sm mb-4">
                    <div class="col-md-6">
                        <label class="small fw-bold">Charge Category</label>
                        <select wire:model.live="charge_category_id" class="form-select shadow-none">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold">Charge Item</label>
                        <select wire:model.live="charge_id" class="form-select shadow-none" @if(!$charge_category_id) disabled @endif>
                            <option value="">Select Charge</option>
                            @foreach($charges as $c) <option value="{{ $c->id }}">{{ $c->name }} (৳{{ $c->standard_charge }})</option> @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6"><label class="small fw-bold">Known Allergies</label><input type="text" wire:model="known_allergies" class="form-control shadow-none"></div>
                    <div class="col-md-6"><label class="small fw-bold">Internal Note</label><input type="text" wire:model="note" class="form-control shadow-none"></div>
                </div>
            </div>

            <!-- Right Panel (Financials) -->
            <div class="col-lg-4 p-4 bg-light">
                <div class="row g-3">
                    <div class="col-12"><label class="small fw-bold">Date & Time</label><input type="datetime-local" wire:model="appointment_date" class="form-control shadow-none @error('appointment_date') is-invalid @enderror"></div>

                    <div class="col-12">
                        <label class="small fw-bold">Consultant Doctor</label>
                        <select wire:model.live="doctor_id" class="form-select shadow-none @error('doctor_id') is-invalid @enderror">
                            <option value="">Select Doctor...</option>
                            @foreach($doctors as $d) <option value="{{ $d->id }}">{{ $d->name }}</option> @endforeach
                        </select>
                    </div>

                    <div class="col-12 bg-white border rounded p-3 my-2 shadow-sm">
                        <div class="d-flex justify-content-between mb-2 small"><span>Doctor/Hospital Fee:</span><span>৳{{ number_format($doctor_fee + $hospital_fee, 2) }}</span></div>
                        <div class="d-flex justify-content-between mb-2 small"><span>Service Charge:</span><span>৳{{ number_format($extra_charge_amount, 2) }}</span></div>
                        <div class="d-flex justify-content-between mb-2 small text-muted"><span>Tax ({{ $tax_percentage }}%):</span><span>৳{{ number_format($tax_amount, 2) }}</span></div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-bold">Discount (%)</span>
                            <input type="number" wire:model.live="discount_percentage" class="form-control w-25 text-center shadow-none" style="height: 30px;">
                        </div>
                        <div class="d-flex justify-content-between small text-danger"><span>Discount Amount:</span><span>- ৳{{ number_format($discount_amount, 2) }}</span></div>
                    </div>

                    <div class="col-12 bg-primary text-white p-4 rounded shadow-lg my-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold">NET PAYABLE:</span>
                        <h3 class="mb-0 fw-bold">৳{{ number_format($net_amount, 2) }}</h3>
                    </div>

                    <div class="col-12">
                        <label class="small fw-bold">Payment Method</label>
                        <select wire:model="payment_method_id" class="form-select shadow-none @error('payment_method_id') is-invalid @enderror">
                            <option value="">Select Method</option>
                            @foreach($paymentMethods as $pm) <option value="{{ $pm->id }}">{{ $pm->name }}</option> @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="small fw-bold text-success">Collection Amount (Paid)</label>
                        <input type="number" wire:model="paid_amount" class="form-control border-success fw-bold text-success fs-4 shadow-none @error('paid_amount') is-invalid @enderror">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 mt-4 fw-bold shadow-sm">
                    FINALIZE ADMISSION
                </button>
            </div>
        </div>
    </form>

    @if($showPatientModal)
    <livewire:admin.common.quick-patient-modal />
    @endif
</div>