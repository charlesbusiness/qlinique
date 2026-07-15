<h6 class="mb-3">Investigation (Lab Tests)</h6>
<div class="table-responsive mb-3">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Name of Test</th>
                <th>Sample/Specimen</th>
                <th>Amount</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lab_tests as $i => $lab)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><input type="text" class="form-control form-control-sm" wire:model="lab_tests.{{ $i }}.name"></td>
                    <td><input type="text" class="form-control form-control-sm" wire:model="lab_tests.{{ $i }}.specimen"></td>
                    <td><input type="number" class="form-control form-control-sm" wire:model="lab_tests.{{ $i }}.amount"></td>
                    <td>
                        @if ($i >= 3)
                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeLabTest({{ $i }})">&times;</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<button type="button" class="btn btn-sm btn-outline-primary mb-3" wire:click="addLabTest">+ Add Test</button>

<div class="mb-3">
    <label class="form-label">Lab Investigation Comment</label>
    <textarea class="form-control" wire:model="lab_investigation_comment" rows="2"></textarea>
</div>

<hr class="my-4">

<h6 class="mb-3">Clinical Judgement (Diagnosis)</h6>
<div class="mb-4">
    <textarea class="form-control" wire:model="clinical_judgement_diagnosis" rows="3" placeholder="Primary diagnosis / clinical notes"></textarea>
</div>

<hr class="my-4">

<h6 class="mb-3">Treatment Plan (Medications)</h6>
<p class="text-muted small">Note: Treatment Plan is SAME as TAKE HOME DRUGS (PLAN).</p>

@foreach ($medications as $i => $med)
    <div class="border rounded p-3 mb-3 bg-light">
        <div class="row g-2 mb-2">
            <div class="col-md-4">
                <label class="form-label small">Route of Administration</label>
                <select class="form-select form-select-sm" wire:model.live="medications.{{ $i }}.route_category">
                    <option value="">— Select —</option>
                    @foreach (\App\Livewire\MaternalHealthForm::$routeCategories as $rVal => $rLabel)
                        <option value="{{ $rVal }}">{{ $rLabel }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small">Form</label>
                <select class="form-select form-select-sm" wire:model="medications.{{ $i }}.route_form">
                    <option value="">— Select —</option>
                    @foreach (\App\Livewire\MaternalHealthForm::routeForms($med['route_category']) as $fVal => $fLabel)
                        <option value="{{ $fVal }}">{{ $fLabel }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small">In-Treatment / Take-Home</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input type="radio" class="form-check-input" wire:model="medications.{{ $i }}.is_take_home" value="0" id="mh_in_{{ $i }}">
                        <label class="form-check-label small" for="mh_in_{{ $i }}">In-Treatment</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" wire:model="medications.{{ $i }}.is_take_home" value="1" id="mh_th_{{ $i }}">
                        <label class="form-check-label small" for="mh_th_{{ $i }}">Take-Home</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-2 mb-2">
            <div class="col-md-3">
                <label class="form-label small">Name of Medication</label>
                <input type="text" class="form-control form-control-sm" wire:model="medications.{{ $i }}.drug_name" placeholder="Drug name...">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Strength</label>
                <select class="form-select form-select-sm" wire:model="medications.{{ $i }}.strength">
                    <option value="">— Select —</option>
                    @foreach (\App\Livewire\MaternalHealthForm::strengthOptions() as $sVal => $sLabel)
                        <option value="{{ $sVal }}">{{ $sLabel }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Dosage</label>
                <input type="text" class="form-control form-control-sm" wire:model="medications.{{ $i }}.dosage" placeholder="e.g. 1 tab">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Regime</label>
                <select class="form-select form-select-sm" wire:model="medications.{{ $i }}.regime">
                    @foreach (\App\Livewire\MaternalHealthForm::$regimeOptions as $rVal => $rLabel)
                        <option value="{{ $rVal }}">{{ $rLabel }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Length</label>
                <div class="input-group input-group-sm">
                    <input type="number" min="1" class="form-control" wire:model="medications.{{ $i }}.length_value" placeholder="Value">
                    <select class="form-select" style="max-width:100px" wire:model="medications.{{ $i }}.length_unit">
                        <option value="days">Days (x/7)</option>
                        <option value="weeks">Weeks (x/52)</option>
                        <option value="months">Months (x/12)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Amount</label>
                <input type="number" step="0.01" class="form-control form-control-sm" wire:model="medications.{{ $i }}.amount" placeholder="0.00">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <span class="badge bg-info">
                    {{ $med['length_value'] ?? 1 }}/{{ ['days' => '7', 'weeks' => '52', 'months' => '12'][$med['length_unit'] ?? 'days'] ?? '7' }}
                </span>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <button type="button" class="btn btn-outline-danger btn-sm" wire:click="removeMedication({{ $i }})">&times;</button>
            </div>
        </div>
    </div>
@endforeach

<button type="button" class="btn btn-outline-primary btn-sm mb-3" wire:click="addMedication">+ Add Drug</button>
