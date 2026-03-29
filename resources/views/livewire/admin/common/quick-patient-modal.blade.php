<div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1050;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg text-dark">
            <div class="modal-header bg-primary p-3">
                <h5 class="modal-title fw-bold text-white">Quick Patient Registration</h5>
                <button type="button" class="btn-close btn-close-white" wire:click="$dispatch('closeModal')"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body p-4 bg-light">
                    <div class="row g-4">
                        <!-- Left Column: Primary Details -->
                        <div class="col-md-8 border-end">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="small fw-bold text-muted">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter name">
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold text-muted">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="017xxxxxxxx">
                                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold text-muted">Gender <span class="text-danger">*</span></label>
                                    <select wire:model="gender_val" class="form-select @error('gender_val') is-invalid @enderror">
                                        <option value="">Select</option>
                                        @foreach($genders as $g) <option value="{{ $g->value }}">{{ $g->value }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold text-muted">Age (Years - Months) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" wire:model="age_year" class="form-control @error('age_year') is-invalid @enderror" placeholder="YY">
                                        <input type="number" wire:model="age_month" class="form-control" placeholder="MM">
                                    </div>
                                    @error('age_year') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold text-muted">Guardian Name</label>
                                    <input type="text" wire:model="guardian_name" class="form-control" placeholder="Father/Husband">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold text-muted">Marital Status</label>
                                    <select wire:model="marital_status" class="form-select">
                                        <option value="">Select</option>
                                        @foreach($marital_statuses as $ms) <option value="{{ $ms->value }}">{{ $ms->value }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="small fw-bold text-muted">Address</label>
                                    <textarea wire:model="address" class="form-control" rows="2" placeholder="Full address..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Medical & TPA Details -->
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="small fw-bold text-muted">Blood Group</label>
                                <select wire:model="blood_group" class="form-select">
                                    <option value="">Select</option>
                                    @foreach($blood_groups as $bg) <option value="{{ $bg->value }}">{{ $bg->value }}</option> @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="small fw-bold text-primary mb-1">Referrer / TPA Selection</label>
                                <div class="dropdown position-relative">
                                    <!-- Search Input Group -->
                                    <div class="input-group input-group-sm border rounded bg-white mb-2">
                                        <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                                        <input type="text"
                                            class="form-control border-0 shadow-none py-2"
                                            placeholder="Search Referrer/TPA..."
                                            wire:model.live.debounce.300ms="tpa_search"
                                            wire:click="$set('showTpaDropdown', true)">

                                        <!-- Clear Button: Only shows if a TPA is selected or search is active -->
                                        @if($tpa_id || $tpa_search)
                                        <button class="btn btn-link text-danger btn-sm border-0 shadow-none" type="button"
                                            wire:click="selectTpa(null, 'No Referrer Selected'); $set('showTpaDropdown', false)">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </button>
                                        @endif
                                    </div>

                                    <!-- Current Selection Display -->
                                    <div class="p-2 border rounded bg-white d-flex align-items-center mb-1">
                                        <div class="{{ $tpa_id ? 'bg-primary' : 'bg-secondary' }} bg-opacity-10 text-{{ $tpa_id ? 'primary' : 'secondary' }} rounded px-2 py-1 me-2">
                                            <i class="bi bi-person-badge"></i>
                                        </div>
                                        <span class="small fw-bold {{ $tpa_id ? 'text-dark' : 'text-muted italic' }}">
                                            {{ $tpa_id ? $selected_tpa_name : 'No Referrer Selected' }}
                                        </span>
                                    </div>

                                    <!-- Dropdown Menu (Controlled by Livewire) -->
                                    <ul class="dropdown-menu w-100 shadow-sm border-light {{ $showTpaDropdown ? 'show' : '' }}"
                                        style="max-height: 200px; overflow-y: auto; display: {{ $showTpaDropdown ? 'block' : 'none' }}; top: 42px; z-index: 1060;">

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
                                        <li class="px-3 py-2 small text-muted text-center">
                                            <i class="bi bi-exclamation-circle me-1"></i> No matches found
                                        </li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>

                            <!-- Outside Click Overlay: Closes dropdown when clicking elsewhere -->
                            @if($showTpaDropdown)
                            <div wire:click="$set('showTpaDropdown', false)"
                                style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 1050; background: transparent;">
                            </div>
                            @endif

                            @if($tpa_id)
                            <div class="mb-3">
                                <label class="small fw-bold text-muted">TPA Validity Date</label>
                                <input type="date" wire:model="tpa_validity" class="form-control border-primary-subtle">
                                <small class="text-muted" style="font-size: 10px;">TPA expiration date</small>
                            </div>
                            @endif

                            <div class="mt-4 p-3 bg-white border rounded border-warning border-opacity-25 shadow-sm">
                                <p class="small text-muted mb-0">
                                    <i class="bi bi-info-circle me-1 text-warning"></i>
                                    Account password will be set to the phone number by default.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top">
                    <button type="button" class="btn btn-light px-4 shadow-sm" wire:click="$dispatch('closeModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm fw-bold">
                        <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                        <i class="bi bi-person-plus me-1"></i> Register & Select
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>