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

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Date of Birth</label>
                                <input type="date" wire:model="date_of_birth" class="form-control shadow-none">
                            </div>

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
                            <label class="form-label small fw-bold text-muted">Patient Photograph</label>
                            <div class="d-flex align-items-center p-3 border rounded bg-white shadow-sm">
                                @if($photo)
                                <img src="{{ $photo->temporaryUrl() }}" class="rounded shadow-sm border" width="70" height="70" style="object-fit: cover;">
                                @elseif($existingPhoto)
                                <img src="{{ asset('storage/'.$existingPhoto) }}" class="rounded shadow-sm border" width="70" height="70" style="object-fit: cover;">
                                @else
                                <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width:70px; height:70px;"><i class="bi bi-person-fill fs-2"></i></div>
                                @endif
                                <div class="ms-3 flex-grow-1">
                                    <input type="file" wire:model="photo" class="form-control form-control-sm">
                                    <div wire:loading wire:target="photo" class="x-small text-primary mt-1">Uploading...</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-danger">Known Allergies</label>
                            <textarea wire:model="known_allergies" class="form-control border-danger-subtle" rows="2" placeholder="List any allergies..."></textarea>
                        </div>

                        <h6 class="text-uppercase text-muted fw-bold mb-3 border-top pt-3 small" style="letter-spacing: 1px;">Referral / TPA</h6>
                        <div class="row g-2">
                            <!-- Manual Dropdown Implementation -->
                            <div class="col-12 mb-2">
                                <label class="small fw-bold text-muted mb-1">Referrer (TPA)</label>
                                <div class="position-relative">
                                    <div class="input-group border rounded shadow-sm bg-white">
                                        <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                                        <input type="text"
                                            class="form-control border-0 shadow-none"
                                            placeholder="Search Referrer..."
                                            wire:model.live.debounce.300ms="tpa_search"
                                            wire:click="$set('showTpaDropdown', true)">
                                        @if($tpa_id || !empty($tpa_search))
                                        <button class="btn btn-link text-danger btn-sm border-0" type="button" wire:click="selectTpa(null, 'No Referrer (Direct)')">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </button>
                                        @endif
                                    </div>

                                    <!-- Selection Badge -->
                                    <div class="mt-2">
                                        <span class="badge {{ $tpa_id ? 'bg-primary' : 'bg-secondary' }} w-100 py-2 text-start px-2 fw-normal">
                                            <i class="bi bi-person-badge me-1"></i> {{ $selected_tpa_name }}
                                        </span>
                                    </div>

                                    <!-- Dropdown Menu -->
                                    <ul class="dropdown-menu w-100 shadow-lg border-light mt-1 {{ $showTpaDropdown ? 'show' : '' }}"
                                        style="max-height: 250px; overflow-y: auto; display: {{ $showTpaDropdown ? 'block' : 'none' }}; z-index: 1060;">
                                        @forelse($tpas as $t)
                                        <li>
                                            <a class="dropdown-item small py-2 d-flex justify-content-between align-items-center"
                                                href="javascript:void(0)"
                                                wire:click="selectTpa({{ $t->id }}, '{{ $t->name }}')">
                                                <span><i class="bi bi-building me-2 text-muted"></i> {{ $t->name }}</span>
                                                @if($tpa_id == $t->id) <i class="bi bi-check-circle-fill text-primary"></i> @endif
                                            </a>
                                        </li>
                                        @empty
                                        <li class="px-3 py-2 small text-muted text-center italic">No matches found</li>
                                        @endforelse
                                    </ul>

                                    <!-- Backdrop to close dropdown -->
                                    @if($showTpaDropdown)
                                    <div wire:click="$set('showTpaDropdown', false)" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 1050; background: transparent;"></div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="small text-muted mb-1">Policy / ID No</label>
                                <input type="text" wire:model="insurance_id" class="form-control" placeholder="Optional">
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted mb-1">TPA Validity</label>
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
    <style>
        .x-small {
            font-size: 11px;
        }

        .italic {
            font-style: italic;
        }
    </style>
</div>