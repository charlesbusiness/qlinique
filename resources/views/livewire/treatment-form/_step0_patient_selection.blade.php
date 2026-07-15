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
    <h5 class="mb-3">Select Assessment Category</h5>

    @if (!$selectedCategory)
        @php $implemented = array_keys(\App\Livewire\TreatmentForm::implementedCategories()); @endphp
        <div class="row g-3">
            @foreach (\App\Livewire\TreatmentForm::assessmentCategories() as $value => $label)
                @php $isImplemented = in_array($value, $implemented); @endphp
                <div class="col-md-4">
                    @if ($isImplemented)
                        <div class="card h-100 shadow-sm border-0 assessment-category-card"
                             style="cursor: pointer; transition: all 0.2s;"
                             wire:click="selectAssessmentCategory('{{ $value }}')"
                             onmouseover="this.classList.add('border-primary', 'shadow')"
                             onmouseout="this.classList.remove('border-primary', 'shadow')">
                            <div class="card-body text-center py-4">
                                <h6 class="card-title mb-0 fw-semibold">{{ $label }}</h6>
                            </div>
                        </div>
                    @else
                        <div class="card h-100 shadow-sm border-0 bg-light"
                             style="cursor: not-allowed; opacity: 0.6;">
                            <div class="card-body text-center py-4">
                                <h6 class="card-title mb-1 fw-semibold text-muted">{{ $label }}</h6>
                                <span class="badge bg-secondary">Coming soon</span>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        @php $subOptions = \App\Livewire\TreatmentForm::assessmentSubOptions($selectedCategory); @endphp

        <div class="mb-3">
            <button type="button" class="btn btn-link btn-sm p-0 mb-3 text-decoration-none" wire:click="goBackToCategories">
                &larr; Back to categories
            </button>
            <h6 class="text-muted mb-3">{{ \App\Livewire\TreatmentForm::assessmentCategories()[$selectedCategory] }}</h6>

            @if (!empty($subOptions))
                @foreach ($subOptions as $value => $label)
                    <div class="form-check mb-2">
                        <input type="radio" class="form-check-input" wire:model.live="selectedSubOption" value="{{ $value }}" id="sub_{{ $value }}">
                        <label class="form-check-label fw-medium" for="sub_{{ $value }}">{{ $label }}</label>
                    </div>
                @endforeach

                @if ($selectedSubOption)
                    <div class="mt-3">
                        <button type="button" class="btn btn-primary" wire:click="selectCategory">Continue</button>
                    </div>
                @endif
            @else
                <div class="mt-3">
                    <button type="button" class="btn btn-primary" wire:click="selectCategory">Continue</button>
                </div>
            @endif
        </div>
    @endif
@endif
