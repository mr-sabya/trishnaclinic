<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">{{ $scheduleId ? 'Edit Schedule' : 'Create New Schedule' }}</h5>
        </div>
        <form wire:submit.prevent="save" class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Doctor *</label>
                    <select wire:model="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror">
                        <option value="">Select Doctor</option>
                        @foreach($doctors as $doc) <option value="{{ $doc->id }}">{{ $doc->name }}</option> @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Global Shift *</label>
                    <select wire:model="global_shift_id" class="form-select @error('global_shift_id') is-invalid @enderror">
                        <option value="">Select Shift</option>
                        @foreach($shifts as $shift) <option value="{{ $shift->id }}">{{ $shift->name }}</option> @endforeach
                    </select>
                </div>

                <div class="col-12 mt-4">
                    <label class="form-label fw-bold mb-3">Available Days *</label>
                    <div class="row g-2">
                        @foreach($days as $day)
                        <div class="col-md-3">
                            <div class="form-check p-2 border rounded {{ in_array($day->value, $available_days) ? 'bg-primary text-white' : 'bg-light' }}">
                                <input class="form-check-input ms-0 me-2" type="checkbox" wire:model.live="available_days" value="{{ $day->value }}" id="day_{{ $day->value }}">
                                <label class="form-check-label fw-bold" for="day_{{ $day->value }}">{{ $day->value }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-3 mt-4">
                    <label class="form-label fw-bold">Start Time *</label>
                    <input type="time" wire:model="start_time" class="form-control">
                </div>
                <div class="col-md-3 mt-4">
                    <label class="form-label fw-bold">End Time *</label>
                    <input type="time" wire:model="end_time" class="form-control">
                </div>
                <div class="col-md-3 mt-4">
                    <label class="form-label fw-bold">Avg Consult Time (Mins)</label>
                    <input type="number" wire:model="avg_consultation_time" class="form-control">
                </div>
                <div class="col-md-3 mt-4">
                    <label class="form-label fw-bold">Max Appts per Day</label>
                    <input type="number" wire:model="max_appointments" class="form-control">
                </div>

                <div class="col-12 mt-4 text-end">
                    <a href="{{ route('admin.doctor-schedules.index') }}" wire:navigate class="btn btn-light me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary px-5">Save Schedule</button>
                </div>
            </div>
        </form>
    </div>
</div>