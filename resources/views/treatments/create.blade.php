<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('New Treatment Chart') }}</h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            @livewire('treatment-form', ['patientId' => $patientId ? (int) $patientId : null])
        </div>
    </div>
</x-app-layout>
