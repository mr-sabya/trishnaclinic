<div class="card shadow-lg border-0">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
        <div class="position-relative col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" wire:model.live.debounce.300ms="patient_search" class="form-control shadow-none" placeholder="Search Patient (Name/MRN)...">
            </div>
            @if(!empty($patient_results))
            <div class="list-group position-absolute w-100 shadow-lg mt-1" style="z-index: 1050;">
                @foreach($patient_results as $p)
                <button type="button" wire:click="selectPatient({{ $p->id }})" class="list-group-item list-group-item-action py-3">
                    <div class="fw-bold">{{ $p->user->name }}</div>
                    <small class="text-muted">MRN: {{ $p->mrn_number }}</small>
                </button>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <form wire:submit.prevent="save" class="card-body p-0">
        <div class="row g-0">
            <!-- Left Side -->
            <div class="col-lg-8 border-end p-4">
                @if($selected_patient_data)
                <div class="bg-light p-3 rounded mb-4 border-start border-primary border-4">
                    <h5 class="fw-bold mb-0">{{ $selected_patient_data->user->name }}</h5>
                    <small>MRN: {{ $selected_patient_data->mrn_number }} | Age: {{ $selected_patient_data->age }}</small>
                </div>
                @endif

                <!-- BED SELECTION -->
                <h6 class="fw-bold mb-3 text-uppercase text-muted small"><i class="bi bi-door-open me-1"></i>Bed Assignment</h6>
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
                    </div>
                </div>

                <!-- (Same Symptoms and Charges logic as OPDManage) -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="small fw-bold">Symptom Category</label>
                        <select wire:model.live="temp_type_id" class="form-select">
                            <option value="">Select</option>
                            @foreach($symptomTypes as $st) <option value="{{ $st->id }}">{{ $st->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold">Symptom Title</label>
                        <div class="input-group">
                            <select wire:model="temp_title_id" class="form-select">
                                <option value="">Select</option>
                                @foreach($symptomTitles as $title) <option value="{{ $title->id }}">{{ $title->title }}</option> @endforeach
                            </select>
                            <button type="button" wire:click="addSymptom" class="btn btn-dark">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side (Pricing) -->
            <div class="col-lg-4 p-4 bg-light">
                <div class="mb-3">
                    <label class="small fw-bold">Admission Date</label>
                    <input type="datetime-local" wire:model="admission_date" class="form-control shadow-none">
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">Consultant Doctor</label>
                    <select wire:model.live="doctor_id" class="form-select shadow-none">
                        <option value="">Select Doctor</option>
                        @foreach($doctors as $d) <option value="{{ $d->id }}">{{ $d->name }}</option> @endforeach
                    </select>
                </div>

                <div class="bg-primary text-white p-4 rounded shadow-sm my-4">
                    <div class="d-flex justify-content-between"><small>Net Total Bill:</small>
                        <h3 class="fw-bold mb-0">৳{{ number_format($net_amount, 2) }}</h3>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="small fw-bold">Advance Payment</label>
                    <input type="number" wire:model="paid_amount" class="form-control form-control-lg border-success text-success fw-bold">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow">FINALIZE ADMISSION</button>
            </div>
        </div>
    </form>
</div>