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
                    let bmi = Math.round((w / ((h / 100) ** 2)) * 10) / 10;
                    bmiEl.value = bmi;
                    $wire.set('bmi', bmi);
                } else {
                    bmiEl.value = '';
                    $wire.set('bmi', null);
                }
            ">
    </div>
    <div class="col-md-3">
        <label class="form-label">Height (cm)</label>
        <input type="number" step="0.1" id="mhHeightInput" class="form-control" wire:model="height"
            x-on:blur="
                let w = parseFloat(document.getElementById('mhWeightInput').value);
                let h = parseFloat($el.value);
                let bmiEl = document.getElementById('mhBmiInput');
                if (w && h && h > 0) {
                    let bmi = Math.round((w / ((h / 100) ** 2)) * 10) / 10;
                    bmiEl.value = bmi;
                    $wire.set('bmi', bmi);
                } else {
                    bmiEl.value = '';
                    $wire.set('bmi', null);
                }
            ">
    </div>
    <div class="col-md-3">
        <label class="form-label">BMI</label>
        <input type="number" step="0.1" id="mhBmiInput" class="form-control" wire:model="bmi" readonly>
    </div>
    <div class="col-md-3">
        <label class="form-label">Comment</label>
        <textarea class="form-control" wire:model="anthropometric_comment" rows="2"></textarea>
    </div>
</div>

<h6 class="mb-3">Rapid Medical Examination (RME)</h6>
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <label class="form-label">FBS (mg/dl)</label>
        <input type="number" step="0.1" class="form-control" wire:model="rme_fbs">
    </div>
    <div class="col-md-2">
        <label class="form-label">RBS (mg/dl)</label>
        <input type="number" step="0.1" class="form-control" wire:model="rme_rbs">
    </div>
    <div class="col-md-2">
        <label class="form-label">PCV %</label>
        <input type="number" step="0.1" class="form-control" wire:model="rme_pcv">
    </div>
    <div class="col-md-2">
        <label class="form-label">RDTA</label>
        <select class="form-select" wire:model="rme_rdta">
            <option value="">--</option>
            <option value="+">+</option>
            <option value="-">-</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Glucose</label>
        <select class="form-select" wire:model="rme_glucose">
            <option value="">--</option>
            <option value="+">+</option>
            <option value="++">++</option>
            <option value="+++">+++</option>
            <option value="-">-</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Protein</label>
        <select class="form-select" wire:model="rme_protein">
            <option value="">--</option>
            <option value="+">+</option>
            <option value="++">++</option>
            <option value="+++">+++</option>
            <option value="-">-</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Leukocytes/Nitrites</label>
        <input type="text" class="form-control" wire:model="rme_leukocytes">
    </div>
    <div class="col-md-3">
        <label class="form-label">Other (Specify)</label>
        <div class="input-group">
            <input type="text" class="form-control" wire:model="rme_other_specify">
            <select class="form-select" style="max-width:70px" wire:model="rme_other_result">
                <option value="">--</option>
                <option value="+">+</option>
                <option value="++">++</option>
                <option value="+++">+++</option>
                <option value="-">-</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">RME Comment</label>
        <textarea class="form-control" wire:model="rme_comment" rows="2"></textarea>
    </div>
</div>
