<button type="button" class="btn btn-link btn-sm p-0 mb-3 text-decoration-none" wire:click="goBackToCategories">
    &larr; Back to categories
</button>

<h5 class="mb-4">Maternal Health Care</h5>

@php
    $subMeta = [
        'antenatal_care' => ['icon' => 'bi-heart-pulse', 'color' => 'pink', 'desc' => 'Pregnancy monitoring and checkups'],
        'labour_delivery' => ['icon' => 'bi-person-raised-hand', 'color' => 'red', 'desc' => 'Delivery and birth support'],
        'postnatal_care' => ['icon' => 'bi-mother', 'color' => 'purple', 'desc' => 'Postpartum recovery care'],
        'infertility' => ['icon' => 'bi-clipboard2-pulse', 'color' => 'teal', 'desc' => 'Fertility assessment and support'],
        'pre_menopause' => ['icon' => 'bi-activity', 'color' => 'indigo', 'desc' => 'Menopause transition care'],
    ];
@endphp

<div class="row g-3">
    @foreach (\App\Livewire\TreatmentForm::assessmentSubOptions('maternal_health') as $value => $label)
        @php $meta = $subMeta[$value] ?? ['icon' => 'bi-folder', 'color' => 'teal', 'desc' => '']; @endphp
        <div class="col-sm-6 col-lg-4">
            <div class="category-card card-body text-center py-4"
                 wire:click="selectMaternalSubCategory('{{ $value }}')">
                <div class="category-icon category-icon-{{ $meta['color'] }}">
                    <i class="bi {{ $meta['icon'] }}"></i>
                </div>
                <div class="category-title">{{ $label }}</div>
                <div class="category-desc">{{ $meta['desc'] }}</div>
            </div>
        </div>
    @endforeach
</div>
