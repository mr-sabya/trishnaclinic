<div class="container-fluid py-4">
    <form wire:submit.prevent="save">
        <div class="row">
            <!-- Left Column: Personal & Identity -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Staff Profile Information</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="text-primary mb-3">1. Basic & Contact Info</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name" class="form-control">
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone (Login ID) <span class="text-danger">*</span></label>
                                <input type="text" wire:model="phone" class="form-control">
                                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" wire:model="email" class="form-control">
                            </div>
                        </div>

                        <h6 class="text-primary mb-3">2. Bangladesh Security Details</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">NID Number <span class="text-danger">*</span></label>
                                <input type="text" wire:model="nid_number" class="form-control">
                                @error('nid_number') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Father's Name <span class="text-danger">*</span></label>
                                <input type="text" wire:model="father_name" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Mother's Name <span class="text-danger">*</span></label>
                                <input type="text" wire:model="mother_name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <div class="d-flex gap-1">
                                    <select wire:model="dob_day" class="form-select">
                                        <option value="">DD</option>
                                        @foreach($days as $d) <option value="{{ sprintf('%02d', $d) }}">{{ $d }}</option> @endforeach
                                    </select>
                                    <select wire:model="dob_month" class="form-select">
                                        <option value="">MM</option>
                                        @foreach($months as $m) <option value="{{ sprintf('%02d', $m) }}">{{ date('F', mktime(0,0,0,$m,1)) }}</option> @endforeach
                                    </select>
                                    <select wire:model="dob_year" class="form-select">
                                        <option value="">YYYY</option>
                                        @foreach($years as $y) <option value="{{ $y }}">{{ $y }}</option> @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select wire:model="gender" class="form-select">
                                    <option value="">Select</option>
                                    @foreach($genders as $g) <option value="{{ $g->value }}">{{ $g->value }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Blood Group</label>
                                <select wire:model="blood_group" class="form-select">
                                    <option value="">Select</option>
                                    @foreach($blood_groups as $bg) <option value="{{ $bg->value }}">{{ $bg->value }}</option> @endforeach
                                </select>
                            </div>
                        </div>

                        <h6 class="text-primary mb-3">3. Address Details</h6>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Present Address</label>
                                <textarea wire:model="present_address" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Permanent Address</label>
                                <textarea wire:model="permanent_address" class="form-control" rows="2"></textarea>
                                <button type="button" class="btn btn-link btn-sm p-0" onclick="@this.set('permanent_address', @this.get('present_address'))">Same as Present</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Employment & Account -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4 text-white bg-primary">
                    <div class="card-body">
                        <label class="form-label">Employee ID</label>
                        <input type="text" class="form-control bg-light" value="{{ $employee_id ?? 'Auto-Generated' }}" readonly disabled>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Employment</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Department <span class="text-danger">*</span></label>
                            <select wire:model="admin_department_id" class="form-select">
                                <option value="">Select Department</option>
                                @foreach($departments as $dept) <option value="{{ $dept->id }}">{{ $dept->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Designation <span class="text-danger">*</span></label>
                            <input type="text" wire:model="designation" class="form-control" placeholder="e.g. Senior Accountant">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Joining Date <span class="text-danger">*</span></label>
                            <input type="date" wire:model="joining_date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Salary (Monthly BDT)</label>
                            <input type="number" wire:model="salary" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4 border-warning">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Account Control</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">System Role <span class="text-danger">*</span></label>
                            <select wire:model="role" class="form-select">
                                <option value="">Select Role</option>
                                @foreach($roles as $r)
                                @if($r->value !== 'patient') <option value="{{ $r->value }}">{{ ucfirst($r->value) }}</option> @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password {{ $userId ? '(Leave blank to keep current)' : '' }}</label>
                            <input type="password" wire:model="password" class="form-control">
                        </div>
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" wire:model="is_active" id="activeStatus">
                            <label class="form-check-label" for="activeStatus">Login Enabled</label>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            Save Staff Member
                        </button>
                        <a href="{{ route('admin.users.index') }}" wire:navigate class="btn btn-light w-100 mt-2">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>