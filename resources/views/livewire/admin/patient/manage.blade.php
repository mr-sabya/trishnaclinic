<div class="">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <form wire:submit.prevent="save">
                <div class="card">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-person-lines-fill me-2"></i>
                            {{ $patientId ? 'Edit Patient Profile: ' . $mrn_number : 'New Patient Registration' }}
                        </h5>
                        <div>
                            <a href="{{ route('admin.patient.index') }}" wire:navigate class="btn btn-light btn-sm me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm px-4">
                                <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                                Save Record
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Left: Identity & Contact -->
                            <div class="col-md-8 border-end">
                                <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.75rem;">Identity & Contact Details</h6>
                                <div class="row g-3">
                                    <div class="col-md-7">
                                        <label class="form-label small">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" wire:model="name" class="form-control  @error('name') is-invalid @enderror" placeholder="Enter Full Name">
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small">Guardian Name</label>
                                        <input type="text" wire:model="guardian_name" class="form-control " placeholder="Father/Husband Name">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label small">Gender <span class="text-danger">*</span></label>
                                        <select wire:model="gender" class="form-select ">
                                            <option value="">Select</option>
                                            @foreach($genders as $g) <option value="{{ $g->value }}">{{ $g->value }}</option> @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small">Date of Birth</label>
                                        <input type="date" wire:model.live="date_of_birth" class="form-control ">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small">Age (Year - Month - Day) <span class="text-danger">*</span></label>
                                        <div class="input-group ">
                                            <input type="number" wire:model.live="age_year" placeholder="YY" class="form-control">
                                            <input type="number" wire:model.live="age_month" placeholder="MM" class="form-control">
                                            <input type="number" wire:model.live="age_day" placeholder="DD" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label small">Blood Group</label>
                                        <select wire:model="blood_group" class="form-select ">
                                            <option value="">Select</option>
                                            @foreach($blood_groups as $bg) <option value="{{ $bg->value }}">{{ $bg->value }}</option> @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small">Marital Status</label>
                                        <select wire:model="marital_status" class="form-select ">
                                            <option value="">Select</option>
                                            @foreach($marital_statuses as $ms) <option value="{{ $ms->value }}">{{ $ms->value }}</option> @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Identification Number (NID/Passport)</label>
                                        <input type="text" wire:model="identification_number" class="form-control " placeholder="National ID or Passport No">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label small">Phone (Login Username) <span class="text-danger">*</span></label>
                                        <input type="text" wire:model="phone" class="form-control  @error('phone') is-invalid @enderror" placeholder="Primary Contact">
                                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label small">Email Address</label>
                                        <input type="email" wire:model="email" class="form-control " placeholder="Patient Email (Optional)">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label small">Permanent Address</label>
                                        <input type="text" wire:model="address" class="form-control " placeholder="House, Road, Area, District">
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Medical & Insurance -->
                            <div class="col-md-4 ps-md-4">
                                <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.75rem;">Medical & Photography</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        <label class="form-label small">Patient Photo</label>
                                        <div class="d-flex align-items-center mb-2">
                                            @if($photo)
                                            <img src="{{ $photo->temporaryUrl() }}" class="rounded border" width="60" height="60">
                                            @elseif($existingPhoto)
                                            <img src="{{ asset('storage/'.$existingPhoto) }}" class="rounded border" width="60" height="60">
                                            @else
                                            <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="width:60px; height:60px;">
                                                <i class="bi bi-camera text-muted"></i>
                                            </div>
                                            @endif
                                            <div class="ms-3">
                                                <input type="file" wire:model="photo" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small text-danger fw-bold">Known Allergies</label>
                                        <textarea wire:model="known_allergies" class="form-control " rows="2" placeholder="Drugs, Food, Dust, etc."></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small">General Remarks</label>
                                        <textarea wire:model="remarks" class="form-control " rows="2" placeholder="Internal medical notes..."></textarea>
                                    </div>
                                </div>

                                <h6 class="text-uppercase text-muted fw-bold mb-3 border-top pt-3" style="font-size: 0.75rem;">Insurance / TPA</h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label small">Organization (TPA)</label>
                                        <select wire:model="tpa_id" class="form-select ">
                                            <option value="">Select TPA</option>
                                            @foreach($tpas as $tpa) <option value="{{ $tpa->id }}">{{ $tpa->name }} ({{ $tpa->code }})</option> @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Policy / Insurance ID</label>
                                        <input type="text" wire:model="insurance_id" class="form-control ">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Validity</label>
                                        <input type="date" wire:model="tpa_validity" class="form-control ">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light py-3 border-0 text-center">
                        <p class="small text-muted mb-0">MRN Number is automatically generated upon saving.</p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>