<div class="mb-4">
    <h5>Select Patient</h5>
    <select class="form-select @error('patientId') is-invalid @enderror" wire:model.live="patientId">
        <option value="">Search patient...</option>
        @foreach ($patients as $p)
            <option value="{{ $p->id }}">{{ $p->file?->file_number ?? 'N/A' }} — {{ $p->name }}</option>
        @endforeach
    </select>
    @error('patientId') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

@if ($patientId)
    <h5 class="mb-3">Select Treatment Category</h5>
    <div class="mb-3">
        @foreach (\App\Livewire\TreatmentForm::subCategoryOptions() as $value => $label)
            <div class="form-check mb-2">
                <input type="radio" class="form-check-input" wire:model.live="sub_category" value="{{ $value }}" id="cat_{{ $value }}">
                <label class="form-check-label fw-medium" for="cat_{{ $value }}">{{ $label }}</label>
            </div>
        @endforeach
    </div>

    @if ($sub_category)
        <button type="button" class="btn btn-primary" wire:click="selectCategory">Continue</button>
    @endif
@endif
