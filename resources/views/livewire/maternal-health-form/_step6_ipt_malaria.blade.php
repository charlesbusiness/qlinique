<hr class="my-4">

<h6 class="mb-1">{{ \App\Livewire\MaternalHealthForm::ordinal($this->doseNumber) }} DOSE OF S.P FOR IPT OF MALARIA</h6>
<p class="text-muted small mb-3">Intermittent Preventive Treatment of Malaria</p>

@foreach ($ipt_medications as $i => $med)
    <div class="border rounded p-3 mb-3 bg-light">
        <div class="row g-2 mb-2">
            <div class="col-md-4">
                <label class="form-label small">Route of Administration</label>
                <select class="form-select form-select-sm" wire:model.live="ipt_medications.{{ $i }}.route_category">
                    <option value="">— Select —</option>
                    @foreach (\App\Livewire\MaternalHealthForm::$routeCategories as $rVal => $rLabel)
                        <option value="{{ $rVal }}">{{ $rLabel }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small">Name of Medication</label>
                <input type="text" class="form-control form-control-sm" wire:model="ipt_medications.{{ $i }}.drug_name" placeholder="Drug name...">
            </div>
            <div class="col-md-4">
                <label class="form-label small">Strength</label>
                <select class="form-select form-select-sm" wire:model="ipt_medications.{{ $i }}.strength">
                    <option value="">— Select —</option>
                    @foreach (\App\Livewire\MaternalHealthForm::strengthOptions() as $sVal => $sLabel)
                        <option value="{{ $sVal }}">{{ $sLabel }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Dosage Regimen</label>
                <input type="text" class="form-control form-control-sm" wire:model="ipt_medications.{{ $i }}.dosage" placeholder="e.g. 1 tab OD x 3 days">
            </div>
            <div class="col-md-4">
                <label class="form-label small">Length</label>
                <div class="input-group input-group-sm">
                    <input type="number" min="1" class="form-control" wire:model="ipt_medications.{{ $i }}.length_value" placeholder="Value">
                    <select class="form-select" style="max-width:100px" wire:model="ipt_medications.{{ $i }}.length_unit">
                        <option value="days">Days (x/7)</option>
                        <option value="weeks">Weeks (x/52)</option>
                        <option value="months">Months (x/12)</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small">Amount</label>
                <div class="input-group input-group-sm">
                    <input type="number" step="0.01" class="form-control" wire:model="ipt_medications.{{ $i }}.amount" placeholder="0.00">
                    <button type="button" class="btn btn-outline-danger" wire:click="removeIptMedication({{ $i }})">&times;</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<button type="button" class="btn btn-outline-primary btn-sm mb-3" wire:click="addIptMedication">+ Add IPT Medication</button>
