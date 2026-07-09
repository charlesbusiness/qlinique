<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">Vital Signs</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Temperature</label>
                <div class="input-group">
                    <input type="number" step="0.1" class="form-control @error('vitals.temperature') is-invalid @enderror" wire:model="vitals.temperature">
                    <select class="form-select" style="max-width: 80px;" wire:model="vitals.temperature_unit">
                        <option value="celsius">°C</option>
                        <option value="fahrenheit">°F</option>
                    </select>
                </div>
                @error('vitals.temperature') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Pulse / Heart Rate (bpm)</label>
                <input type="number" class="form-control @error('vitals.pulse_rate') is-invalid @enderror" wire:model="vitals.pulse_rate">
                @error('vitals.pulse_rate') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Respiration (bpm)</label>
                <input type="number" class="form-control @error('vitals.respiratory_rate') is-invalid @enderror" wire:model="vitals.respiratory_rate">
                @error('vitals.respiratory_rate') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Blood Pressure (Systolic) (mmHg)</label>
                <input type="number" class="form-control @error('vitals.blood_pressure_systolic') is-invalid @enderror" wire:model="vitals.blood_pressure_systolic">
                @error('vitals.blood_pressure_systolic') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Blood Pressure (Diastolic) (mmHg)</label>
                <input type="number" class="form-control @error('vitals.blood_pressure_diastolic') is-invalid @enderror" wire:model="vitals.blood_pressure_diastolic">
                @error('vitals.blood_pressure_diastolic') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Oxygen Saturation (%)</label>
                <input type="number" class="form-control @error('vitals.oxygen_saturation') is-invalid @enderror" wire:model="vitals.oxygen_saturation">
                @error('vitals.oxygen_saturation') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Comment</label>
            <textarea class="form-control" wire:model="vitals.comment" rows="2" placeholder="Additional notes on vitals..."></textarea>
        </div>
    </div>
</div>
