<h6 class="mb-3">Pregnancy Dating & Current Status</h6>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label">Last Menstrual Period (LMP)</label>
        <input type="date" class="form-control" wire:model="lmp">
    </div>
    <div class="col-md-4">
        <label class="form-label">Cycle Regularity</label>
        <div class="d-flex gap-3">
            <div class="form-check">
                <input type="radio" class="form-check-input" wire:model="cycle_regularity" value="Regular" id="cycle_reg">
                <label class="form-check-label" for="cycle_reg">Regular</label>
            </div>
            <div class="form-check">
                <input type="radio" class="form-check-input" wire:model="cycle_regularity" value="Irregular" id="cycle_irreg">
                <label class="form-check-label" for="cycle_irreg">Irregular</label>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">Expected Date of Delivery (EDD)</label>
        <input type="date" class="form-control" wire:model="edd">
    </div>
    <div class="col-md-3">
        <label class="form-label">CGA Weeks</label>
        <input type="number" class="form-control" wire:model.live="cga_weeks" min="0" max="45">
    </div>
    <div class="col-md-3">
        <label class="form-label">CGA Days</label>
        <input type="number" class="form-control" wire:model.live="cga_days" min="0" max="6">
    </div>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold">Current Symptoms</label>
    @foreach (\App\Livewire\MaternalHealthForm::$currentSymptomOptions as $value => $label)
        <div class="form-check">
            <input type="checkbox" class="form-check-input" wire:model="current_symptoms" value="{{ $value }}" id="sym_{{ $value }}">
            <label class="form-check-label" for="sym_{{ $value }}">{{ $label }}</label>
        </div>
    @endforeach
</div>

<div class="mb-4">
    <label class="form-label">Medications/Exposures since LMP</label>
    <textarea class="form-control" wire:model="medications_exposures" rows="2"></textarea>
</div>

<hr class="my-4">

<h6 class="mb-3">Obstetric History (GTPAL)</h6>
<div class="row g-3 mb-4">
    <div class="col">
        <label class="form-label">Gravida (G)</label>
        <select class="form-select" wire:model="gravida">
            <option value="">--</option>
            @for ($i = 0; $i <= 6; $i++)
                <option value="{{ $i }}">{{ $i }}{{ $i === 6 ? '+' : '' }}</option>
            @endfor
        </select>
    </div>
    <div class="col">
        <label class="form-label">Term (T)</label>
        <select class="form-select" wire:model="term">
            <option value="">--</option>
            @for ($i = 0; $i <= 4; $i++)
                <option value="{{ $i }}">{{ $i }}{{ $i === 4 ? '+' : '' }}</option>
            @endfor
        </select>
    </div>
    <div class="col">
        <label class="form-label">Preterm (P)</label>
        <select class="form-select" wire:model="preterm">
            <option value="">--</option>
            @for ($i = 0; $i <= 3; $i++)
                <option value="{{ $i }}">{{ $i }}{{ $i === 3 ? '+' : '' }}</option>
            @endfor
        </select>
    </div>
    <div class="col">
        <label class="form-label">Abortion (A)</label>
        <select class="form-select" wire:model="abortion">
            <option value="">--</option>
            @for ($i = 0; $i <= 3; $i++)
                <option value="{{ $i }}">{{ $i }}{{ $i === 3 ? '+' : '' }}</option>
            @endfor
        </select>
    </div>
    <div class="col">
        <label class="form-label">Living (L)</label>
        <select class="form-select" wire:model="living">
            <option value="">--</option>
            @for ($i = 0; $i <= 4; $i++)
                <option value="{{ $i }}">{{ $i }}{{ $i === 4 ? '+' : '' }}</option>
            @endfor
        </select>
    </div>
</div>

<h6 class="mb-3">Previous Pregnancies</h6>
<div class="table-responsive mb-3">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Year</th>
                <th>Gest. Age (Weeks)</th>
                <th>Mode of Delivery</th>
                <th>Birth Weight</th>
                <th>Complications</th>
                <th>Neonatal Outcome</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($prior_pregnancies as $i => $preg)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><input type="date" class="form-control form-control-sm" wire:model="prior_pregnancies.{{ $i }}.year"></td>
                    <td><input type="number" class="form-control form-control-sm" wire:model="prior_pregnancies.{{ $i }}.gest_age"></td>
                    <td>
                        <select class="form-select form-select-sm" wire:model="prior_pregnancies.{{ $i }}.mode_of_delivery">
                            <option value="">--</option>
                            <option value="Vaginal">Vaginal</option>
                            <option value="C-Section">C-Section</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control form-control-sm" wire:model="prior_pregnancies.{{ $i }}.birth_weight"></td>
                    <td><input type="text" class="form-control form-control-sm" wire:model="prior_pregnancies.{{ $i }}.complications"></td>
                    <td><input type="text" class="form-control form-control-sm" wire:model="prior_pregnancies.{{ $i }}.neonatal_outcome"></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removePriorPregnancy({{ $i }})">&times;</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<button type="button" class="btn btn-sm btn-outline-primary mb-4" wire:click="addPriorPregnancy">+ Add Row</button>

<div class="mb-3">
    <label class="form-label fw-semibold">History of Prior C-Section / Uterine Surgery?</label>
    <div class="d-flex gap-3 mb-2">
        <div class="form-check">
            <input type="radio" class="form-check-input" wire:model="prior_csection" value="No" id="csec_no">
            <label class="form-check-label" for="csec_no">No</label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" wire:model="prior_csection" value="Yes" id="csec_yes">
            <label class="form-check-label" for="csec_yes">Yes</label>
        </div>
    </div>
    @if ($prior_csection === 'Yes')
        <input type="text" class="form-control" wire:model="prior_csection_details" placeholder="Specify date & indication">
    @endif
</div>
