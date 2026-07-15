<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('New Maternal Health Assessment') }}</h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            @livewire('maternal-health-form', ['patientId' => $patientId ? (int) $patientId : null, 'subOption' => $subOption])
        </div>
    </div>
</x-app-layout>
