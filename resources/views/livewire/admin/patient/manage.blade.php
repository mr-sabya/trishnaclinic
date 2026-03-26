<div class="container-fluid">
    <form wire:submit.prevent="save">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-person-lines-fill me-2"></i>
                    {{ $patientId ? 'Edit Patient: ' . $mrn_display : 'New Patient Registration' }}
                </h5>
                <div>
                    <a href="{{ route('admin.patient.index') }}" wire:navigate class="btn btn-light btn-sm me-2 px-3">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">
                        <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                        <i class="bi bi-check-circle me-1"></i> Save Record
                    </button>
                </div>
            </div>

            <div class="card-body p-4 bg-white">
                <div class="row">
                    <!-- Identity Section -->
                    <div class="col-md-8 border-end">
                        <h6 class="text-uppercase text-muted fw-bold mb-4 small" style="letter-spacing: 1px;">Primary Details</h6>

                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label small fw-bold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="John Doe">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-5">
                                <label class="form-label small fw-bold">Guardian Name</label>
                                <input type="text" wire:model="guardian_name" class="form-control" placeholder="Father/Husband Name">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Gender <span class="text-danger">*</span></label>
                                <select wire:model="gender" class="form-select @error('gender') is-invalid @enderror">
                                    <option value="">Select</option>
                                    @foreach($genders as $g) <option value="{{ $g->value }}">{{ $g->value }}</option> @endforeach
                                </select>
                            </div>

                            <!-- DATE OF BIRTH (Independent) -->
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Date of Birth</label>
                                <input type="date" wire:model="date_of_birth" class="form-control shadow-none">
                            </div>

                            <!-- AGE (Independent YY-MM) -->
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Age (Years - Months)</label>
                                <div class="input-group">
                                    <input type="number" wire:model="age_year" placeholder="YY" class="form-control shadow-none">
                                    <input type="number" wire:model="age_month" placeholder="MM" class="form-control shadow-none">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Blood Group</label>
                                <select wire:model="blood_group" class="form-select">
                                    <option value="">Select</option>
                                    @foreach($blood_groups as $bg) <option value="{{ $bg->value }}">{{ $bg->value }}</option> @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Marital Status</label>
                                <select wire:model="marital_status" class="form-select">
                                    <option value="">Select</option>
                                    @foreach($marital_statuses as $ms) <option value="{{ $ms->value }}">{{ $ms->value }}</option> @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">NID / Passport Number</label>
                                <input type="text" wire:model="identification_number" class="form-control" placeholder="Identification No">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                    <input type="text" wire:model="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="017xxxxxxxx">
                                </div>
                                @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email Address</label>
                                <input type="email" wire:model="email" class="form-control" placeholder="Optional">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold">Present Address</label>
                                <textarea wire:model="address" class="form-control" rows="2" placeholder="Street, City, District..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Side Section -->
                    <div class="col-md-4 ps-md-4 bg-light-subtle">
                        <h6 class="text-uppercase text-muted fw-bold mb-4 small" style="letter-spacing: 1px;">Medical & Photo</h6>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Patient Photograph</label>
                            <div class="d-flex align-items-center p-3 border rounded bg-white">
                                @if($photo)
                                <img src="{{ $photo->temporaryUrl() }}" class="rounded shadow-sm" width="70" height="70" style="object-fit: cover;">
                                @elseif($existingPhoto)
                                <img src="{{ asset('storage/'.$existingPhoto) }}" class="rounded shadow-sm" width="70" height="70" style="object-fit: cover;">
                                @else
                                <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width:70px; height:70px;"><i class="bi bi-person-fill fs-2"></i></div>
                                @endif
                                <div class="ms-3 flex-grow-1">
                                    <input type="file" wire:model="photo" class="form-control form-control-sm">
                                    <div wire:loading wire:target="photo" class="small text-primary mt-1">Uploading...</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-danger">Known Allergies</label>
                            <textarea wire:model="known_allergies" class="form-control border-danger-subtle" rows="2" placeholder="List any allergies..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">General Remarks</label>
                            <textarea wire:model="remarks" class="form-control" rows="2"></textarea>
                        </div>

                        <h6 class="text-uppercase text-muted fw-bold mb-3 border-top pt-3 small" style="letter-spacing: 1px;">Insurance (TPA)</h6>
                        <div class="row g-2">
                            <div class="col-12 mb-2">
                                <label class="small text-muted mb-1">Organization</label>
                                <select wire:model="tpa_id" class="form-select">
                                    <option value="">Direct/Cash</option>
                                    @foreach($tpas as $t) <option value="{{ $t->id }}">{{ $t->name }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted mb-1">Policy No</label>
                                <input type="text" wire:model="insurance_id" class="form-control" placeholder="ID">
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted mb-1">Validity</label>
                                <input type="date" wire:model="tpa_validity" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light py-2 text-center border-top-0">
                <small class="text-muted">Fields marked with <span class="text-danger">*</span> are mandatory.</small>
            </div>
        </div>
    </form>
</div>