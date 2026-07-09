<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">TREATMENT PLAN</h5>
        <p class="text-muted small">Note: Treatment Plan is SAME as TAKE HOME DRUGS (PLAN).</p>

        @foreach ($treatmentPlanItems as $i => $item)
            <div class="border rounded p-3 mb-3 bg-light">
                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <label class="form-label small">Route of Administration</label>
                        <select class="form-select form-select-sm" wire:model.live="treatmentPlanItems.{{ $i }}.route_category">
                            <option value="">— Select —</option>
                            @foreach (\App\Livewire\TreatmentForm::routeCategories() as $rVal => $rLabel)
                                <option value="{{ $rVal }}">{{ $rLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Form</label>
                        <select class="form-select form-select-sm" wire:model="treatmentPlanItems.{{ $i }}.route_form">
                            <option value="">— Select —</option>
                            @foreach (\App\Livewire\TreatmentForm::routeForms($item['route_category']) as $fVal => $fLabel)
                                <option value="{{ $fVal }}">{{ $fLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">In-Treatment / Take-Home</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" wire:model="treatmentPlanItems.{{ $i }}.is_take_home" value="0" id="tpi_in_{{ $i }}">
                                <label class="form-check-label small" for="tpi_in_{{ $i }}">In-Treatment</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" wire:model="treatmentPlanItems.{{ $i }}.is_take_home" value="1" id="tpi_th_{{ $i }}">
                                <label class="form-check-label small" for="tpi_th_{{ $i }}">Take-Home</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-3">
                        <label class="form-label small">Name of Medication</label>
                        <input type="text" class="form-control form-control-sm" wire:model="treatmentPlanItems.{{ $i }}.drug_name" placeholder="Drug name...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Strength</label>
                        <select class="form-select form-select-sm" wire:model="treatmentPlanItems.{{ $i }}.strength">
                            <option value="">— Select —</option>
                            @foreach (\App\Livewire\TreatmentForm::strengthOptions() as $sVal => $sLabel)
                                <option value="{{ $sVal }}">{{ $sLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Dosage</label>
                        <input type="text" class="form-control form-control-sm" wire:model="treatmentPlanItems.{{ $i }}.dosage" placeholder="e.g. 1 tab">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Regime</label>
                        <select class="form-select form-select-sm" wire:model="treatmentPlanItems.{{ $i }}.regime">
                            @foreach (\App\Livewire\TreatmentForm::regimeOptions() as $rVal => $rLabel)
                                <option value="{{ $rVal }}">{{ $rLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Length</label>
                        <div class="input-group input-group-sm">
                            <input type="number" min="1" class="form-control" wire:model="treatmentPlanItems.{{ $i }}.length_value" placeholder="Value">
                            <select class="form-select" style="max-width: 100px;" wire:model="treatmentPlanItems.{{ $i }}.length_unit">
                                <option value="days">Days (x/7)</option>
                                <option value="weeks">Weeks (x/52)</option>
                                <option value="months">Months (x/12)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small">Amount (₦)</label>
                        <input type="number" step="0.01" class="form-control form-control-sm" wire:model.blur="treatmentPlanItems.{{ $i }}.amount" placeholder="0.00">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <span class="badge bg-info">
                            {{ $item['length_value'] ?? 1 }}/{{ ['days' => '7', 'weeks' => '52', 'months' => '12'][$item['length_unit'] ?? 'days'] ?? '7' }}
                        </span>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-danger btn-sm" wire:click="removeTreatmentPlanItem({{ $i }})">&times;</button>
                    </div>
                </div>
            </div>
        @endforeach

        <button type="button" class="btn btn-outline-primary btn-sm mb-3" wire:click="addTreatmentPlanItem">+ Add Drug</button>
    </div>
</div>
