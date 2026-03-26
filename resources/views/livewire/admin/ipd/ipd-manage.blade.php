<div class="card shadow-lg border-0 position-relative">
    <!-- Header with Search and New Patient Button -->
    <div class="card-header bg-white py-3 border-bottom">
        <div class="row align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-primary"></i></span>
                    <input type="text" wire:model.live.debounce.300ms="patient_search"
                        class="form-control border-start-0 shadow-none"
                        placeholder="Search Patient (Name/MRN/Phone)...">

                    <!-- NEW PATIENT BUTTON -->
                    <button type="button" class="btn btn-outline-primary" wire:click="$set('showPatientModal', true)">
                        <i class="bi bi-person-plus-fill me-1"></i> New
                    </button>
                </div>

                @if(!empty($patient_results))
                <div class="list-group position-absolute shadow-lg mt-1 w-100" style="z-index: 1060; max-width: 400px;">
                    @foreach($patient_results as $p)
                    <button type="button" wire:click="selectPatient({{ $p->id }})" class="list-group-item list-group-item-action py-3 border-start-0 border-end-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold text-primary">{{ $p->user->name }}</div>
                                <small class="text-muted">MRN: {{ $p->mrn_number }} | Ph: {{ $p->user->phone }}</small>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </div>
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="col-md-7 text-end">
                <span class="badge bg-soft-primary text-primary border border-primary px-3 py-2">IPD ADMISSION FORM</span>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="save">
        <div class="row g-0">
            <!-- Left Side: Patient & Medical Info -->
            <div class="col-lg-8 border-end p-4">

                <!-- Selected Patient Display -->
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

                <!-- BED SELECTION -->
                <h6 class="fw-bold mb-3 text-uppercase text-muted small"><i class="bi bi-door-open me-1"></i> Bed Assignment</h6>
                <div class="row g-3 p-3 bg-white border rounded shadow-sm mb-4">
                    <div class="col-md-4">
                        <label class="small fw-bold">Floor</label>
                        <select wire:model.live="floor_id" class="form-select shadow-none">
                            <option value="">Select Floor</option>
                            @foreach($floors as $f) <option value="{{ $f->id }}">{{ $f->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold">Bed Group (Ward)</label>
                        <select wire:model.live="bed_group_id" class="form-select shadow-none" @if(!$floor_id) disabled @endif>
                            <option value="">Select Group</option>
                            @foreach($bedGroups as $bg) <option value="{{ $bg->id }}">{{ $bg->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold">Select Bed <span class="text-danger">*</span></label>
                        <select wire:model="bed_id" class="form-select shadow-none @error('bed_id') is-invalid @enderror" @if(!$bed_group_id) disabled @endif>
                            <option value="">Select Bed</option>
                            @foreach($availableBeds as $bed)
                            <option value="{{ $bed->id }}">{{ $bed->name }}</option>
                            @endforeach
                        </select>
                        @error('bed_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- SYMPTOMS SECTION -->
                <h6 class="fw-bold mb-3 text-uppercase text-muted small"><i class="bi bi-thermometer-high me-1"></i> Symptoms & Complaints</h6>
                <div class="row g-3 p-3 bg-white border rounded shadow-sm mb-4">
                    <div class="col-md-5">
                        <label class="small fw-bold">Type</label>
                        <select wire:model.live="temp_type_id" class="form-select shadow-none">
                            <option value="">Select</option>
                            @foreach($symptomTypes as $st) <option value="{{ $st->id }}">{{ $st->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="small fw-bold">Symptom</label>
                        <select wire:model="temp_title_id" class="form-select shadow-none">
                            <option value="">Select</option>
                            @foreach($symptomTitles as $title) <option value="{{ $title->id }}">{{ $title->title }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" wire:click="addSymptom" class="btn btn-dark w-100">Add</button>
                    </div>

                    @if(!empty($added_symptoms))
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mt-2 mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Title</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($added_symptoms as $index => $s)
                                    <tr>
                                        <td>{{ $s['type_name'] }}</td>
                                        <td>{{ $s['title_name'] }}</td>
                                        <td class="text-center"><button type="button" class="btn btn-sm btn-danger px-1 py-0" wire:click="removeSymptom({{ $index }})"><i class="bi bi-x"></i></button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- EXTRA CHARGES -->
                <h6 class="fw-bold mb-3 text-uppercase text-muted small"><i class="bi bi-plus-circle me-1"></i> Admission / Service Charges</h6>
                <div class="row g-3 p-3 bg-white border rounded shadow-sm">
                    <div class="col-md-6">
                        <label class="small fw-bold">Charge Category</label>
                        <select wire:model.live="charge_category_id" class="form-select shadow-none">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold">Charge Name</label>
                        <select wire:model.live="charge_id" class="form-select shadow-none" @if(!$charge_category_id) disabled @endif>
                            <option value="">Select Charge</option>
                            @foreach($charges as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Right Side: Pricing & Finalize -->
            <div class="col-lg-4 p-4 bg-light">
                <div class="mb-3">
                    <label class="small fw-bold text-muted">ADMISSION DATE <span class="text-danger">*</span></label>
                    <input type="datetime-local" wire:model="admission_date" class="form-control shadow-none @error('admission_date') is-invalid @enderror">
                </div>

                <div class="mb-3">
                    <label class="small fw-bold text-muted">CONSULTANT DOCTOR <span class="text-danger">*</span></label>
                    <select wire:model.live="doctor_id" class="form-select shadow-none @error('doctor_id') is-invalid @enderror">
                        <option value="">Select Doctor</option>
                        @foreach($doctors as $d) <option value="{{ $d->id }}">{{ $d->user->name ?? $d->name }}</option> @endforeach
                    </select>
                </div>

                <hr>

                <!-- Financial Breakdown -->
                <div class="p-3 bg-white rounded shadow-sm border mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Consultation Fee:</span>
                        <span class="fw-bold">৳{{ number_format($doctor_fee, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Admission Fee:</span>
                        <span class="fw-bold">৳{{ number_format($hospital_fee, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Extra Charge:</span>
                        <span class="fw-bold">৳{{ number_format($extra_charge_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-danger">
                        <span>Tax ({{ $tax_percentage }}%):</span>
                        <span class="fw-bold">৳{{ number_format($tax_amount, 2) }}</span>
                    </div>
                    @if($discount_percentage > 0)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Discount ({{ $discount_percentage }}%):</span>
                        <span class="fw-bold">-৳{{ number_format($discount_amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                        <h5 class="fw-bold mb-0">Grand Total:</h5>
                        <h5 class="fw-bold mb-0 text-primary">৳{{ number_format($net_amount, 2) }}</h5>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="small fw-bold">Payment Method <span class="text-danger">*</span></label>
                    <select wire:model="payment_method_id" class="form-select shadow-none @error('payment_method_id') is-invalid @enderror">
                        @foreach($paymentMethods as $pm) <option value="{{ $pm->id }}">{{ $pm->name }}</option> @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="small fw-bold text-success">ADVANCE PAYMENT <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-success text-white border-success">৳</span>
                        <input type="number" wire:model="paid_amount" class="form-control form-control-lg border-success text-success fw-bold shadow-none @error('paid_amount') is-invalid @enderror">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-lg">
                    <span wire:loading.remove wire:target="save"><i class="bi bi-check-circle me-1"></i> FINALIZE ADMISSION</span>
                    <span wire:loading wire:target="save" class="spinner-border spinner-border-sm"></span>
                </button>
            </div>
        </div>
    </form>

    <!-- Modal Inclusion -->
    @if($showPatientModal)
    @livewire('admin.common.quick-patient-modal')
    @endif
</div>