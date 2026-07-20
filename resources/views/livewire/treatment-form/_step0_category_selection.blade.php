@if (!$selectedCategory)
    <h5 class="mb-4">Select Assessment Category</h5>

    @php
        $implemented = array_keys(\App\Livewire\TreatmentForm::implementedCategories());
        $categoryMeta = [
            'checkup' => ['icon' => 'bi-clipboard-check', 'color' => 'green', 'desc' => 'Routine health check and screening'],
            'treatment' => ['icon' => 'bi-capsule', 'color' => 'blue', 'desc' => 'Mild ailments, palliative & home care'],
            'maternal_health' => ['icon' => 'bi-heart-pulse', 'color' => 'pink', 'desc' => 'Antenatal, labour, postnatal care'],
            'enrollment_palliative' => ['icon' => 'bi-heart', 'color' => 'orange', 'desc' => 'Chronic disease management'],
            'emergency_accident' => ['icon' => 'bi-exclamation-triangle', 'color' => 'red', 'desc' => 'Emergency and accident response'],
            'consultancy' => ['icon' => 'bi-chat-dots', 'color' => 'purple', 'desc' => 'Counseling, education, and advice'],
        ];
    @endphp

    <div class="row g-3">
        @foreach (\App\Livewire\TreatmentForm::assessmentCategories() as $value => $label)
            @php
                $isImplemented = in_array($value, $implemented);
                $meta = $categoryMeta[$value] ?? ['icon' => 'bi-folder', 'color' => 'teal', 'desc' => ''];
            @endphp
            <div class="col-sm-6 col-lg-4">
                @if ($isImplemented)
                    <div class="category-card card-body text-center py-4"
                         wire:click="selectAssessmentCategory('{{ $value }}')">
                        <div class="category-icon category-icon-{{ $meta['color'] }}">
                            <i class="bi {{ $meta['icon'] }}"></i>
                        </div>
                        <div class="category-title">{{ $label }}</div>
                        <div class="category-desc">{{ $meta['desc'] }}</div>
                    </div>
                @else
                    <div class="category-card disabled card-body text-center py-4">
                        <div class="category-icon category-icon-{{ $meta['color'] }}">
                            <i class="bi {{ $meta['icon'] }}"></i>
                        </div>
                        <div class="category-title">{{ $label }}</div>
                        <div class="category-desc">{{ $meta['desc'] }}</div>
                        <span class="badge bg-secondary mt-2">Coming soon</span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

@elseif (!$patientId)
    <button type="button" class="btn btn-link btn-sm p-0 mb-3 text-decoration-none" wire:click="goBackToCategories">
        &larr; Back to categories
    </button>

    <h5 class="mb-4">Select Patient</h5>
    <select class="form-select @error('patientId') is-invalid @enderror" wire:model.live="patientId">
        <option value="">Search patient...</option>
        @foreach ($patients as $p)
            <option value="{{ $p->id }}">{{ $p->file?->file_number ?? 'N/A' }} — {{ $p->name }}</option>
        @endforeach
    </select>
    @error('patientId') <div class="invalid-feedback">{{ $message }}</div> @enderror

    @if ($patientId)
        @php $subOptions = \App\Livewire\TreatmentForm::assessmentSubOptions($selectedCategory); @endphp

        @if (!empty($subOptions))
            <h6 class="text-muted mt-4 mb-3">{{ \App\Livewire\TreatmentForm::assessmentCategories()[$selectedCategory] }}</h6>

            @foreach ($subOptions as $value => $label)
                <div class="form-check mb-2">
                    <input type="radio" class="form-check-input" wire:model.live="selectedSubOption" value="{{ $value }}" id="sub_{{ $value }}">
                    <label class="form-check-label fw-medium" for="sub_{{ $value }}">{{ $label }}</label>
                </div>
            @endforeach

            @if ($selectedSubOption)
                <div class="mt-3">
                    <button type="button" class="btn btn-primary" wire:click="selectCategory">
                        Continue with {{ $patients->firstWhere('id', $patientId)?->name }}
                    </button>
                </div>
            @endif
        @else
            <div class="mt-3">
                <button type="button" class="btn btn-primary" wire:click="selectCategory">
                    Continue with {{ $patients->firstWhere('id', $patientId)?->name }}
                </button>
            </div>
        @endif
    @endif
@endif
