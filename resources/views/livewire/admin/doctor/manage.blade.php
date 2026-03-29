<div>
    <form wire:submit.prevent="save">
        <div class="row g-3">
            <!-- Left Side: Profile & Professional -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">Doctor Profile Information</h6>
                        <span class="badge {{ $type == 'permanent' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12 mb-2">
                                <label class="form-label fw-bold">Doctor Classification *</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check border rounded px-3 py-2 flex-grow-1">
                                        <input class="form-check-input" type="radio" wire:model="type" value="permanent" id="typePerm">
                                        <label class="form-check-label fw-bold" for="typePerm">Permanent Staff</label>
                                    </div>
                                    <div class="form-check border rounded px-3 py-2 flex-grow-1">
                                        <input class="form-check-input" type="radio" wire:model="type" value="on_call" id="typeCall">
                                        <label class="form-check-label fw-bold" for="typeCall">On-Call / Visiting</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Full Name *</label>
                                <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone (Username) *</label>
                                <input type="text" wire:model="phone" class="form-control @error('phone') is-invalid @enderror">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Gender</label>
                                <select wire:model="gender" class="form-select">
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Medical Department *</label>
                                <select wire:model="medical_department_id" class="form-select @error('medical_department_id') is-invalid @enderror">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept) <option value="{{ $dept->id }}">{{ $dept->name }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Specialist Category *</label>
                                <select wire:model="specialist_id" class="form-select @error('specialist_id') is-invalid @enderror">
                                    <option value="">Select Specialist</option>
                                    @foreach($specialists as $spec) <option value="{{ $spec->id }}">{{ $spec->name }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Designation *</label>
                                <input type="text" wire:model="designation" class="form-control" placeholder="e.g. Senior Consultant">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Experience</label>
                                <input type="text" wire:model="experience" class="form-control" placeholder="e.g. 10 Years">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Address</label>
                                <textarea wire:model="address" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Qualification *</label>
                                <livewire:quill-text-editor wire:model.live="qualification" theme="snow" />
                                <style>
                                    .ql-editor {
                                        height: 150px;
                                    }
                                </style>
                                <small class="text-muted">e.g. MBBS, FCPS (Medicine)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fee Configuration Section -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold"><i class="ri-money-dollar-circle-line me-1"></i> Fee Structure (Doctor / Hospital Split)</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-4 text-center">
                            <div class="col-md-4 border-end">
                                <p class="small fw-bold text-primary mb-2">APPOINTMENT</p>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Dr</span>
                                    <input type="number" wire:model="appointment_doctor_fee" class="form-control">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text">Hosp</span>
                                    <input type="number" wire:model="appointment_hospital_fee" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4 border-end">
                                <p class="small fw-bold text-primary mb-2">OPD VISIT</p>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Dr</span>
                                    <input type="number" wire:model="opd_doctor_fee" class="form-control">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text">Hosp</span>
                                    <input type="number" wire:model="opd_hospital_fee" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <p class="small fw-bold text-primary mb-2">IPD (ADMISSION)</p>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Dr</span>
                                    <input type="number" wire:model="ipd_doctor_fee" class="form-control">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text">Hosp</span>
                                    <input type="number" wire:model="ipd_hospital_fee" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-3 text-center p-4">
                    <label class="form-label fw-bold d-block text-start">Doctor Photo</label>
                    <div class="mb-3">
                        @if($photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="rounded-circle border shadow-sm" width="120" height="120" style="object-fit: cover;">
                        @elseif($existingPhoto)
                        <img src="{{ asset('storage/'.$existingPhoto) }}" class="rounded-circle border shadow-sm" width="120" height="120" style="object-fit: cover;">
                        @else
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto border" style="width:120px; height:120px;">
                            <i class="ri-user-follow-line fs-1 text-muted"></i>
                        </div>
                        @endif
                    </div>
                    <input type="file" wire:model="photo" class="form-control">
                    @error('photo') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="card shadow-sm border-0 p-3">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" wire:model="is_active" id="isActive">
                        <label class="form-check-label fw-bold" for="isActive">Available for Service</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm fw-bold">
                        <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                        <i class="ri-save-line me-1"></i> {{ $doctorId ? 'Update Profile' : 'Register Doctor' }}
                    </button>
                    <a href="{{ route('admin.doctor.index') }}" wire:navigate class="btn btn-light w-100 mt-2">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>