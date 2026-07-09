@php
    $eentConds = \App\Livewire\TreatmentForm::eentConditions();
    $eentParts = ['eyes' => 'EYES', 'ears' => 'EARS', 'nose' => 'NOSE', 'throat' => 'THROAT'];
@endphp

{{-- (1) Anthropometry --}}
<div class="card mb-3" x-data>
    <div class="card-body">
        <h5 class="card-title">(1) PHYSICAL EXAMINATION — Anthropometry</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Weight (kg) <span class="text-danger">*</span></label>
                <input type="number" step="0.1" id="weightInput"
                    class="form-control @error('vitals.weight') is-invalid @enderror"
                    wire:model.blur="vitals.weight"
                    x-on:blur="
                        let w = parseFloat($el.value);
                        let h = parseFloat($el.closest('.row').querySelector('#heightInput').value);
                        let bmiEl = $el.closest('.row').querySelector('#bmiInput');
                        if (w && h && h > 0) {
                            let bmi = Math.round((w / ((h / 100) ** 2)) * 10) / 10;
                            bmiEl.value = bmi;
                            $wire.set('vitals.bmi', bmi);
                        } else {
                            bmiEl.value = '';
                            $wire.set('vitals.bmi', null);
                        }
                    ">
                @error('vitals.weight') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Height (cm) <span class="text-danger">*</span></label>
                <input type="number" step="0.1" id="heightInput"
                    class="form-control @error('vitals.height') is-invalid @enderror"
                    wire:model.blur="vitals.height"
                    x-on:blur="
                        let w = parseFloat($el.closest('.row').querySelector('#weightInput').value);
                        let h = parseFloat($el.value);
                        let bmiEl = $el.closest('.row').querySelector('#bmiInput');
                        if (w && h && h > 0) {
                            let bmi = Math.round((w / ((h / 100) ** 2)) * 10) / 10;
                            bmiEl.value = bmi;
                            $wire.set('vitals.bmi', bmi);
                        } else {
                            bmiEl.value = '';
                            $wire.set('vitals.bmi', null);
                        }
                    ">
                @error('vitals.height') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">BMI</label>
                <input type="number" step="0.1" id="bmiInput" class="form-control" wire:model="vitals.bmi" readonly>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Comment</label>
            <textarea class="form-control" wire:model="anthropometryComment" rows="2"></textarea>
        </div>
    </div>
</div>

{{-- (2) Heart & Lungs --}}
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">(2) HEART AND LUNGS</h5>
        <div class="row">
            @foreach (['lub_dub' => 'Lub – Dub', 'murmur_swishing' => 'Murmur/Swishing', 'arrhythmia_irregular' => 'Arrhythmia/Irregular', 'crackles_fluid' => 'Crackles/Fluid Sound'] as $val => $label)
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" wire:model="heartLungsFindings" value="{{ $val }}" id="hl_{{ $val }}">
                        <label class="form-check-label" for="hl_{{ $val }}">{{ $label }}</label>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-2">
            <label class="form-label">Comment</label>
            <textarea class="form-control" wire:model="heartLungsComment" rows="2"></textarea>
        </div>
    </div>
</div>

{{-- (3) Eyes / Ears / Nose / Throat --}}
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">(3) EYES / EARS / NOSE / THROAT</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Condition</th>
                        @foreach ($eentParts as $partKey => $partLabel)
                            <th class="text-center small">{{ $partLabel }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($eentConds as $condKey => $condLabel)
                        <tr>
                            <th class="align-middle">{{ $condLabel }}</th>
                            @foreach ($eentParts as $partKey => $partLabel)
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input" wire:model="eent{{ ucfirst($partKey) }}Findings" value="{{ $condKey }}">
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-2">
            <label class="form-label">Comment</label>
            <textarea class="form-control" wire:model="eentComment" rows="2"></textarea>
        </div>
    </div>
</div>
