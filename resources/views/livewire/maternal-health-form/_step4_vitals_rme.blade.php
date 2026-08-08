<h6 class="mb-3">Vital Signs</h6>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <label class="form-label">Temperature</label>
        <div class="input-group">
            <input type="number" step="0.1" class="form-control" wire:model="temperature">
            <select class="form-select" style="max-width:80px" wire:model="temperature_unit">
                <option value="celsius">&deg;C</option>
                <option value="fahrenheit">&deg;F</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <label class="form-label">Pulse (bpm)</label>
        <input type="number" class="form-control" wire:model="pulse_bpm">
    </div>
    <div class="col-md-3">
        <label class="form-label">Respiration (bpm)</label>
        <input type="number" class="form-control" wire:model="respiration_bpm">
    </div>
    <div class="col-md-3">
        <label class="form-label">SpO2 (%)</label>
        <input type="number" class="form-control" wire:model="oxygen_saturation">
    </div>
    <div class="col-md-3">
        <label class="form-label">BP Systolic (mmHg)</label>
        <input type="number" class="form-control" wire:model="bp_systolic">
    </div>
    <div class="col-md-3">
        <label class="form-label">BP Diastolic (mmHg)</label>
        <input type="number" class="form-control" wire:model="bp_diastolic">
    </div>
    <div class="col-md-6">
        <label class="form-label">Comment</label>
        <textarea class="form-control" wire:model="vitals_comment" rows="2"></textarea>
    </div>
</div>

<h6 class="mb-3">Anthropometric Measure</h6>
<div class="row g-3 mb-4" x-data>
    <div class="col-md-3">
        <label class="form-label">Weight (kg)</label>
        <input type="number" step="0.1" id="mhWeightInput" class="form-control" wire:model="weight"
            x-on:blur="
                let w = parseFloat($el.value);
                let h = parseFloat(document.getElementById('mhHeightInput').value);
                let bmiEl = document.getElementById('mhBmiInput');
                if (w && h && h > 0) {
                    let bmi = Math.round((w / (h ** 2)) * 10) / 10;
                    bmiEl.value = bmi;
                    $wire.set('bmi', bmi);
                    let bmiRange = bmi < 18.5 ? 'underweight' : bmi <= 24.9 ? 'normal healthy weight' : bmi < 30 ? 'overweight' : 'obese';
                    $wire.set('bmi_range', bmiRange);
                } else {
                    bmiEl.value = '';
                    $wire.set('bmi', null);
                    $wire.set('bmi_range', '');
                }
            ">
    </div>
    <div class="col-md-3">
        <label class="form-label">Height (m)</label>
        <input type="number" step="0.01" id="mhHeightInput" class="form-control" wire:model="height"
            x-on:blur="
                let w = parseFloat(document.getElementById('mhWeightInput').value);
                let h = parseFloat($el.value);
                let bmiEl = document.getElementById('mhBmiInput');
                if (w && h && h > 0) {
                    let bmi = Math.round((w / (h ** 2)) * 10) / 10;
                    bmiEl.value = bmi;
                    $wire.set('bmi', bmi);
                    let bmiRange = bmi < 18.5 ? 'underweight' : bmi <= 24.9 ? 'normal healthy weight' : bmi < 30 ? 'overweight' : 'obese';
                    $wire.set('bmi_range', bmiRange);
                } else {
                    bmiEl.value = '';
                    $wire.set('bmi', null);
                    $wire.set('bmi_range', '');
                }
            ">
    </div>
    <div class="col-md-3">
        <label class="form-label">BMI</label>
        <input type="number" step="0.1" id="mhBmiInput" class="form-control" wire:model="bmi" readonly>
    </div>
    <div class="col-md-6">
        <label class="form-label">BMI Range</label>
        <div class="d-flex flex-wrap gap-3">
            <label class="form-check">
                <input type="radio" class="form-check-input" value="underweight" wire:model="bmi_range">
                <span class="form-check-label">&lt; 18.5 (Underweight)</span>
            </label>
            <label class="form-check">
                <input type="radio" class="form-check-input" value="normal healthy weight" wire:model="bmi_range">
                <span class="form-check-label">18.5 – 24.9 (Normal healthy weight)</span>
            </label>
            <label class="form-check">
                <input type="radio" class="form-check-input" value="overweight" wire:model="bmi_range">
                <span class="form-check-label">25.0 – 29.9 (Overweight)</span>
            </label>
            <label class="form-check">
                <input type="radio" class="form-check-input" value="obese" wire:model="bmi_range">
                <span class="form-check-label">30.0 &amp; above (Obese)</span>
            </label>
        </div>
    </div>
    <div class="col-md-3">
        <label class="form-label">Comment</label>
        <textarea class="form-control" wire:model="anthropometric_comment" rows="2"></textarea>
    </div>
</div>

<h6 class="mb-3">Rapid Medical Examination (RME)</h6>
<div class="mb-2">
    <select class="form-select form-select-sm" wire:model.live="rmeNewTest">
        <option value="">Select test...</option>
        @foreach (\App\Livewire\TreatmentForm::rmeTestOptions() as $val)
            <option value="{{ $val }}">{{ $val }}</option>
        @endforeach
    </select>
</div>
@if (count($rme_tests))
    <div class="table-responsive mb-3">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Test</th>
                    <th>Result</th>
                    <th style="width:25%">Amount (&#8358;)</th>
                    <th style="width:60px"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rme_tests as $i => $rme)
                    <tr>
                        <td class="fw-medium">{{ $rme['test_name'] }}</td>
                        <td>
                            <input type="text" class="form-control form-control-sm" wire:model="rme_tests.{{ $i }}.result" placeholder="Result / (+/-)">
                        </td>
                        <td>
                            <input type="number" step="0.01" class="form-control form-control-sm" wire:model.blur="rme_tests.{{ $i }}.amount" placeholder="0.00">
                        </td>
                        <td class="align-middle text-center">
                            <button type="button" class="btn btn-outline-danger btn-sm" wire:click="removeRmeTest({{ $i }})">&times;</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
<div class="mb-4">
    <button type="button" class="btn btn-outline-primary btn-sm" wire:click="addRmeTest">+ Add</button>
</div>
<div class="mb-4">
    <label class="form-label">RME Comment</label>
    <textarea class="form-control" wire:model="rme_comment" rows="2"></textarea>
</div>
