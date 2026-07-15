<h6 class="mb-3">Comprehensive Physical Examination</h6>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <label class="form-label fw-semibold">1. Cardiovascular & Respiratory</label>
        @foreach (\App\Livewire\MaternalHealthForm::$cardioRespOptions as $value => $label)
            <div class="form-check">
                <input type="checkbox" class="form-check-input" wire:model="cardio_resp" value="{{ $value }}" id="cardio_{{ $value }}">
                <label class="form-check-label" for="cardio_{{ $value }}">{{ $label }}</label>
            </div>
        @endforeach
        <textarea class="form-control mt-2" wire:model="cardio_resp_comment" rows="2" placeholder="Comment"></textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">2. Thyroid</label>
        @foreach (\App\Livewire\MaternalHealthForm::$thyroidOptions as $value => $label)
            <div class="form-check">
                <input type="checkbox" class="form-check-input" wire:model="thyroid" value="{{ $value }}" id="thyroid_{{ $value }}">
                <label class="form-check-label" for="thyroid_{{ $value }}">{{ $label }}</label>
            </div>
        @endforeach
        <textarea class="form-control mt-2" wire:model="thyroid_comment" rows="2" placeholder="Comment"></textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">3. Breast</label>
        @foreach (\App\Livewire\MaternalHealthForm::$breastOptions as $value => $label)
            <div class="form-check">
                <input type="checkbox" class="form-check-input" wire:model="breast" value="{{ $value }}" id="breast_{{ $value }}">
                <label class="form-check-label" for="breast_{{ $value }}">{{ $label }}</label>
            </div>
        @endforeach
        <textarea class="form-control mt-2" wire:model="breast_comment" rows="2" placeholder="Comment"></textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">4. Extremities</label>
        @foreach (\App\Livewire\MaternalHealthForm::$extremitiesOptions as $value => $label)
            <div class="form-check">
                <input type="checkbox" class="form-check-input" wire:model="extremities" value="{{ $value }}" id="ext_{{ $value }}">
                <label class="form-check-label" for="ext_{{ $value }}">{{ $label }}</label>
            </div>
        @endforeach
        <textarea class="form-control mt-2" wire:model="extremities_comment" rows="2" placeholder="Comment"></textarea>
    </div>
</div>

<hr class="my-4">

<h6 class="mb-3">Obstetric / Abdominal Examination</h6>

<div class="mb-4">
    <label class="form-label fw-semibold">Fundal Height Measurement (SFH)</label>
    <small class="text-muted d-block mb-2">Measured from symphysis pubis to uterine fundus. Between 20-36 weeks, height in cm typically correlates to gestational age in weeks (&plusmn; 2cm).</small>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Fundal Height (cm)</label>
            <input type="number" step="0.1" class="form-control" wire:model="fundal_height_cm">
        </div>
    </div>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold">Leopold's Maneuvers</label>
    <small class="text-muted d-block mb-2">Performed from 32-36 weeks to determine fetal lie, presentation, position, and engagement.</small>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Fetal Lie</label>
            <select class="form-select" wire:model="fetal_lie">
                <option value="">-- Select --</option>
                <option value="Longitudinal">Longitudinal</option>
                <option value="Transverse">Transverse</option>
                <option value="Oblique">Oblique</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Presentation</label>
            <select class="form-select" wire:model="fetal_presentation">
                <option value="">-- Select --</option>
                <option value="Cephalic (head down)">Cephalic (head down)</option>
                <option value="Breech (bottom down)">Breech (bottom down)</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Position</label>
            <input type="text" class="form-control" wire:model="fetal_position" placeholder="Direction fetal back is facing">
        </div>
        <div class="col-md-3">
            <label class="form-label">Engagement</label>
            <input type="text" class="form-control" wire:model="fetal_engagement" placeholder="Descent into maternal pelvis">
        </div>
    </div>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold">Fetal Heart Rate (FHR)</label>
    <small class="text-muted d-block mb-2">Audible via Doppler from 10-12 weeks. Normal: 110-160 bpm.</small>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">FHR (bpm)</label>
            <input type="number" class="form-control" wire:model="fetal_heart_rate_bpm">
        </div>
    </div>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold">Pelvic & Vaginal Examination</label>
    <textarea class="form-control" wire:model="pelvic_vaginal_findings" rows="3" placeholder="Findings"></textarea>
</div>
