<div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg text-dark">
            <div class="modal-header bg-primary p-3">
                <h5 class="modal-title fw-bold text-white">Quick Patient Registration</h5>
                <button type="button" class="btn-close btn-close-white" wire:click="$dispatch('closeModal')"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3">
                        <div class="col-md-8 border-end">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="small fw-bold">Full Name *</label>
                                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Phone *</label>
                                    <input type="text" wire:model="phone" class="form-control @error('phone') is-invalid @enderror">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Gender *</label>
                                    <select wire:model="gender_val" class="form-select @error('gender_val') is-invalid @enderror">
                                        <option value="">Select</option>
                                        @foreach($genders as $g) <option value="{{ $g->value }}">{{ $g->value }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Age (Years - Months) *</label>
                                    <div class="input-group">
                                        <input type="number" wire:model="age_year" class="form-control @error('age_year') is-invalid @enderror" placeholder="YY">
                                        <input type="number" wire:model="age_month" class="form-control" placeholder="MM">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Guardian Name</label>
                                    <input type="text" wire:model="guardian_name" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Marital Status</label>
                                    <select wire:model="marital_status" class="form-select">
                                        <option value="">Select</option>
                                        @foreach($marital_statuses as $ms) <option value="{{ $ms->value }}">{{ $ms->value }}</option> @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="small fw-bold">Address</label>
                                <textarea wire:model="address" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold">Blood Group</label>
                                <select wire:model="blood_group" class="form-select">
                                    <option value="">Select</option>
                                    @foreach($blood_groups as $bg) <option value="{{ $bg->value }}">{{ $bg->value }}</option> @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" wire:click="$dispatch('closeModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                        Register & Select
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>