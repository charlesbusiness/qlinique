<h6 class="mb-3">Chronic Medical Conditions</h6>
<div class="mb-3">
    <div class="d-flex flex-wrap gap-3">
        @foreach (\App\Livewire\MaternalHealthForm::$chronicConditionOptions as $value => $label)
            <div class="form-check">
                <input type="checkbox" class="form-check-input" wire:model="chronic_conditions" value="{{ $value }}" id="chronic_{{ $value }}">
                <label class="form-check-label" for="chronic_{{ $value }}">{{ $label }}</label>
            </div>
        @endforeach
    </div>
    <div class="mt-2">
        <textarea class="form-control" wire:model="chronic_conditions_details" rows="2" placeholder="Details/Other"></textarea>
    </div>
</div>

<h6 class="mb-3">Infectious Disease History</h6>
<div class="mb-3">
    <div class="d-flex flex-wrap gap-3">
        @foreach (\App\Livewire\MaternalHealthForm::$infectiousDiseaseOptions as $value => $label)
            <div class="form-check">
                <input type="checkbox" class="form-check-input" wire:model="infectious_disease_history" value="{{ $value }}" id="inf_{{ $value }}">
                <label class="form-check-label" for="inf_{{ $value }}">{{ $label }}</label>
            </div>
        @endforeach
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Prior Non-Obstetric Surgeries</label>
    <textarea class="form-control" wire:model="prior_surgeries" rows="2"></textarea>
</div>

<div class="mb-3">
    <label class="form-label">Allergies (Medication & Latex)</label>
    <textarea class="form-control" wire:model="allergies" rows="2"></textarea>
</div>

<div class="mb-3">
    <label class="form-label">Current Medications & Supplements</label>
    <textarea class="form-control" wire:model="current_medications" rows="2"></textarea>
</div>

<hr class="my-4">

<h6 class="mb-3">Family & Genetic History</h6>
<div class="mb-3">
    @foreach (\App\Livewire\MaternalHealthForm::$familyGeneticOptions as $value => $label)
        <div class="form-check">
            <input type="checkbox" class="form-check-input" wire:model="family_genetic_history" value="{{ $value }}" id="fam_{{ $value }}">
            <label class="form-check-label" for="fam_{{ $value }}">{{ $label }}</label>
        </div>
    @endforeach
    <div class="mt-2">
        <textarea class="form-control" wire:model="family_history_notes" rows="2" placeholder="Notes on family history"></textarea>
    </div>
</div>

<hr class="my-4">

<h6 class="mb-3">Social & Environmental History</h6>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Tobacco/Vape</label>
        <div class="d-flex gap-3 align-items-center">
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="tobacco_vape" value="Never" id="tob_never"><label class="form-check-label" for="tob_never">Never</label></div>
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="tobacco_vape" value="Former" id="tob_former"><label class="form-check-label" for="tob_former">Former</label></div>
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="tobacco_vape" value="Current" id="tob_current"><label class="form-check-label" for="tob_current">Current</label></div>
            @if ($tobacco_vape === 'Current')
                <input type="number" class="form-control form-control-sm" style="width:80px" wire:model="tobacco_packs_per_day" placeholder="packs/day">
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Alcohol</label>
        <div class="d-flex gap-3 align-items-center">
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="alcohol" value="No" id="alc_no"><label class="form-check-label" for="alc_no">No</label></div>
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="alcohol" value="Yes" id="alc_yes"><label class="form-check-label" for="alc_yes">Yes</label></div>
            @if ($alcohol === 'Yes')
                <input type="number" class="form-control form-control-sm" style="width:80px" wire:model="alcohol_drinks_per_week" placeholder="drinks/week">
            @endif
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Recreational Drugs</label>
        <div class="d-flex gap-3">
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="recreational_drugs" value="No" id="drug_no"><label class="form-check-label" for="drug_no">No</label></div>
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="recreational_drugs" value="Yes" id="drug_yes"><label class="form-check-label" for="drug_yes">Yes</label></div>
        </div>
        @if ($recreational_drugs === 'Yes')
            <input type="text" class="form-control mt-2" wire:model="recreational_drugs_details" placeholder="Specify substance & route">
        @endif
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Support System</label>
        <div class="d-flex gap-3">
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="support_system" value="Strong" id="sup_strong"><label class="form-check-label" for="sup_strong">Strong</label></div>
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="support_system" value="Limited" id="sup_limited"><label class="form-check-label" for="sup_limited">Limited</label></div>
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="support_system" value="None" id="sup_none"><label class="form-check-label" for="sup_none">None</label></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label class="form-label fw-semibold">Safety: Do you feel safe at home?</label>
        <div class="d-flex gap-3">
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="safety_screening" value="Yes" id="safe_yes"><label class="form-check-label" for="safe_yes">Yes</label></div>
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="safety_screening" value="No" id="safe_no"><label class="form-check-label" for="safe_no">No</label></div>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Financial Stability</label>
        <div class="d-flex gap-3">
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="financial_stability" value="Satisfied" id="fin_sat"><label class="form-check-label" for="fin_sat">Satisfied</label></div>
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="financial_stability" value="Fear" id="fin_fear"><label class="form-check-label" for="fin_fear">Fear</label></div>
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="financial_stability" value="Unstable" id="fin_unsat"><label class="form-check-label" for="fin_unsat">Unstable</label></div>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Intimate Partner Violence</label>
        <div class="d-flex gap-3">
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="intimate_partner_violence" value="Yes" id="ipv_yes"><label class="form-check-label" for="ipv_yes">Yes</label></div>
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="intimate_partner_violence" value="No" id="ipv_no"><label class="form-check-label" for="ipv_no">No</label></div>
        </div>
        @if ($intimate_partner_violence === 'Yes')
            <input type="text" class="form-control mt-2" wire:model="ipv_details" placeholder="Details">
        @endif
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Occupation & Workplace Hazard</label>
        <div class="d-flex gap-3">
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="occupation_hazard" value="Stress Free" id="occ_free"><label class="form-check-label" for="occ_free">Stress Free</label></div>
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="occupation_hazard" value="High Stress Level" id="occ_stress"><label class="form-check-label" for="occ_stress">High Stress</label></div>
            <div class="form-check"><input type="radio" class="form-check-input" wire:model="occupation_hazard" value="Sedentary Habit" id="occ_sed"><label class="form-check-label" for="occ_sed">Sedentary</label></div>
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Recent Travel History (Endemic Infectious Zones)</label>
        <textarea class="form-control" wire:model="travel_history" rows="2"></textarea>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Diet Intake</label>
        @foreach (\App\Livewire\MaternalHealthForm::$dietIntakeOptions as $value => $label)
            <div class="form-check">
                <input type="checkbox" class="form-check-input" wire:model="diet_intake" value="{{ $value }}" id="diet_{{ $value }}">
                <label class="form-check-label" for="diet_{{ $value }}">{{ $label }}</label>
            </div>
        @endforeach
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Physical Activities</label>
        @foreach (\App\Livewire\MaternalHealthForm::$physicalActivityOptions as $value => $label)
            <div class="form-check">
                <input type="checkbox" class="form-check-input" wire:model="physical_activities" value="{{ $value }}" id="phys_{{ $value }}">
                <label class="form-check-label" for="phys_{{ $value }}">{{ $label }}</label>
            </div>
        @endforeach
    </div>
</div>
