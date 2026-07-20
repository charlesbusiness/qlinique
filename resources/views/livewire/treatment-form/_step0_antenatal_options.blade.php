<button type="button" class="btn btn-link btn-sm p-0 mb-3 text-decoration-none" wire:click="goBackToMaternalSubCategories">
    &larr; Back to Maternal Health sub-categories
</button>

<h5 class="mb-4">Antenatal Care</h5>
<p class="text-muted mb-4">Select an option:</p>

@php
    $options = [
        ['key' => 'registration', 'icon' => 'bi-person-plus', 'color' => 'green', 'title' => 'Antenatal Registration', 'desc' => 'Register a new antenatal patient', 'action' => "selectAntenatalOption('registration')"],
        ['key' => 'first_contact', 'icon' => 'bi-clipboard-plus', 'color' => 'blue', 'title' => 'ANC First Contact', 'desc' => 'First antenatal assessment for a patient', 'action' => "selectAntenatalFirstContact()"],
    ];
@endphp

@foreach ($options as $opt)
    <div class="option-list-item d-flex align-items-center"
         wire:click="{{ $opt['action'] }}">
        <div class="option-icon category-icon-{{ $opt['color'] }} me-3">
            <i class="bi {{ $opt['icon'] }}"></i>
        </div>
        <div>
            <h6 class="mb-1 fw-semibold">{{ $opt['title'] }}</h6>
            <small class="text-muted">{{ $opt['desc'] }}</small>
        </div>
    </div>
@endforeach
