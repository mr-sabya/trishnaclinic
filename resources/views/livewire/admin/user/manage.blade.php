<div>
    <form wire:submit.prevent="save">
        <div class="row g-3">
            <!-- LEFT COLUMN: Identity, Personal, Education & Documents -->
            <div class="col-lg-8">

                <!-- 1. Identity & Personal Details -->
                <div class="card mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="ri-user-settings-line me-2"></i> Staff Identity & Personal Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="John Doe">
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Gender <span class="text-danger">*</span></label>
                                <select wire:model="gender" class="form-select @error('gender') is-invalid @enderror">
                                    <option value="">Select</option>
                                    @foreach($genders as $g) <option value="{{ $g->value }}">{{ $g->value }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone (Login ID / Username) <span class="text-danger">*</span></label>
                                <input type="text" wire:model="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="017xxxxxxxx">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" wire:model="email" class="form-control" placeholder="example@mail.com">
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">NID Number <span class="text-danger">*</span></label>
                                <input type="text" wire:model="nid_number" class="form-control @error('nid_number') is-invalid @enderror">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Date of Birth <span class="text-danger">*</span></label>
                                <div class="d-flex gap-2">
                                    <select wire:model="dob_day" class="form-select">
                                        <option value="">Day</option>
                                        @foreach($days as $d) <option value="{{ sprintf('%02d', $d) }}">{{ $d }}</option> @endforeach
                                    </select>
                                    <select wire:model="dob_month" class="form-select">
                                        <option value="">Month</option>
                                        @foreach($months as $m) <option value="{{ sprintf('%02d', $m) }}">{{ date('F', mktime(0,0,0,$m,1)) }}</option> @endforeach
                                    </select>
                                    <select wire:model="dob_year" class="form-select">
                                        <option value="">Year</option>
                                        @foreach($years as $y) <option value="{{ $y }}">{{ $y }}</option> @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Father's Name</label>
                                <input type="text" wire:model="father_name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Mother's Name</label>
                                <input type="text" wire:model="mother_name" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted">Blood Group</label>
                                <select wire:model="blood_group" class="form-select">
                                    <option value="">Select</option>
                                    @foreach($blood_groups as $bg) <option value="{{ $bg->value }}">{{ $bg->value }}</option> @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Address Details -->
                <div class="card mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-primary"><i class="ri-map-pin-user-line me-2"></i> Address Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Present Address</label>
                                <textarea wire:model="present_address" class="form-control" rows="2" placeholder="House, Road, Area, District..."></textarea>
                            </div>
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-bold mb-0">Permanent Address</label>
                                    <button type="button" class="btn btn-sm btn-outline-primary py-0" style="font-size: 10px;" onclick="@this.set('permanent_address', @this.get('present_address'))">Same as Present</button>
                                </div>
                                <textarea wire:model="permanent_address" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Education / Qualifications -->
                <div class="card mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-primary"><i class="ri-medal-line me-2"></i> Educational Qualifications</h6>
                    </div>
                    <div class="card-body">
                        <livewire:quill-text-editor wire:model.live="qualification" theme="snow" />
                        <style>
                            .ql-editor {
                                min-height: 150px;
                            }
                        </style>
                    </div>
                </div>

                <!-- 4. Attachments & Preview Section -->
                <div class="card mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-primary"><i class="ri-attachment-line me-2"></i> Staff Documents & Remarks</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Upload Documents (CV, NID, Certificates)</label>
                            <div class="upload-zone p-4 border-2 border-dashed rounded text-center position-relative mb-3">
                                <input type="file" wire:model="documents" class="position-absolute opacity-0 w-100 h-100 start-0 top-0" style="cursor: pointer;" multiple>
                                <i class="ri-upload-cloud-2-line fs-1 text-primary"></i>
                                <p class="mb-0 small fw-bold">Click or Drag & Drop multiple files here</p>
                                <p class="text-muted x-small">PDF, JPG, PNG (Max 2MB per file)</p>
                            </div>
                            <div wire:loading wire:target="documents" class="text-primary small mb-2">
                                <span class="spinner-border spinner-border-sm me-1"></span> Processing file previews...
                            </div>
                        </div>

                        <!-- Grid Preview for Documents -->
                        <div class="row g-3">
                            <!-- New Uploads Preview -->
                            @foreach($documents as $index => $doc)
                            <div class="col-6 col-md-3">
                                <div class="card h-100 border shadow-sm position-relative overflow-hidden">
                                    <button type="button" wire:click="removeNewDocument({{ $index }})" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 z-3 shadow-sm" style="padding: 2px 5px;">
                                        <i class="ri-close-line"></i>
                                    </button>
                                    <div class="ratio ratio-1x1 bg-dark d-flex align-items-center justify-content-center">
                                        @if($this->isImage($doc))
                                        <img src="{{ $doc->temporaryUrl() }}" class="img-fluid object-fit-cover">
                                        @else
                                        <div class="text-white text-center p-2">
                                            <i class="ri-file-pdf-2-line fs-1"></i>
                                            <div class="x-small text-truncate mt-1 px-1">{{ $doc->getClientOriginalName() }}</div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="card-footer py-1 px-2 bg-warning text-dark x-small fw-bold text-center border-0">New Upload</div>
                                </div>
                            </div>
                            @endforeach

                            <!-- Existing Saved Files Preview -->
                            @foreach($existingDocuments as $index => $path)
                            <div class="col-6 col-md-3">
                                <div class="card h-100 border shadow-sm position-relative overflow-hidden">
                                    <button type="button" wire:click="removeExistingDocument({{ $index }})" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 z-3 shadow-sm" style="padding: 2px 5px;">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                    <div class="ratio ratio-1x1 bg-secondary d-flex align-items-center justify-content-center">
                                        @if($this->isImage($path))
                                        <img src="{{ asset('storage/'.$path) }}" class="img-fluid object-fit-cover">
                                        @else
                                        <a href="{{ asset('storage/'.$path) }}" target="_blank" class="text-white text-center text-decoration-none p-2">
                                            <i class="ri-file-list-3-line fs-1"></i>
                                            <div class="x-small mt-1 px-1">View Stored File</div>
                                        </a>
                                        @endif
                                    </div>
                                    <div class="card-footer py-1 px-2 bg-light text-muted x-small text-center border-0">Saved File</div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-bold">General Remarks / Others</label>
                            <textarea wire:model="remarks" class="form-control" rows="3" placeholder="Additional notes about staff member..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Photo, Employment & Account -->
            <div class="col-lg-4">

                <!-- 5. Profile Photo -->
                <div class="card mb-4 text-center p-4">
                    <div class="position-relative d-inline-block mx-auto mb-3">
                        @if($photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="rounded-circle border shadow-sm p-1" width="140" height="140" style="object-fit: cover;">
                        @elseif($existingPhoto)
                        <img src="{{ asset('storage/'.$existingPhoto) }}" class="rounded-circle border shadow-sm p-1" width="140" height="140" style="object-fit: cover;">
                        @else
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border shadow-sm" style="width:140px; height:140px;">
                            <i class="ri-user-add-line fs-1 text-muted"></i>
                        </div>
                        @endif
                    </div>
                    <label class="form-label fw-bold d-block mb-2">Staff Profile Photo</label>
                    <input type="file" wire:model="photo" class="form-control form-control-sm">
                    @error('photo') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- 6. Employment Details -->
                <div class="card mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-primary">Employment Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Employee ID</label>
                            <input type="text" class="form-control bg-light fw-bold" value="{{ $employee_id ?? 'Auto-Generated' }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Department <span class="text-danger">*</span></label>
                            <select wire:model="admin_department_id" class="form-select @error('admin_department_id') is-invalid @enderror">
                                <option value="">Select Dept</option>
                                @foreach($departments as $dept) <option value="{{ $dept->id }}">{{ $dept->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Designation <span class="text-danger">*</span></label>
                            <input type="text" wire:model="designation" class="form-control" placeholder="Nurse, Accountant, etc.">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Salary (Monthly BDT)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">৳</span>
                                <input type="number" wire:model="salary" class="form-control">
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Joining Date</label>
                            <input type="date" wire:model="joining_date" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- 7. Account Control -->
                <div class="card mb-4 border-top border-primary border-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">System Role <span class="text-danger">*</span></label>
                            <select wire:model="role" class="form-select">
                                <option value="">Select Role</option>
                                @foreach($roles as $r)
                                @if(!in_array($r->value, ['patient', 'doctor']))
                                <option value="{{ $r->value }}">{{ ucfirst($r->value) }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Login Password</label>
                            <input type="password" wire:model="password" class="form-control" placeholder="{{ $userId ? 'Leave blank for current' : 'Default is Phone' }}">
                        </div>
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" wire:model="is_active" id="activeSw">
                            <label class="form-check-label fw-bold ms-1" for="activeSw">Login Enabled</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            <i class="ri-save-line me-1"></i> Save Staff Member
                        </button>
                        <a href="{{ route('admin.users.index') }}" wire:navigate class="btn btn-light w-100 mt-2">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <!-- STYLES FOR PREVIEW GRID AND DROPZONE -->
    <style>
        .x-small {
            font-size: 11px;
        }

        .object-fit-cover {
            object-fit: cover;
        }

        .border-dashed {
            border-style: dashed !important;
            border-width: 2px !important;
        }

        .upload-zone:hover {
            background-color: #f8faff !important;
            border-color: #0d6efd !important;
        }

        .upload-zone i {
            transition: transform 0.3s ease;
        }

        .upload-zone:hover i {
            transform: translateY(-5px);
        }

        .z-3 {
            z-index: 3;
        }
    </style>

</div>