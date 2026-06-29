<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Register New Patient') }}</h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            @livewire('patient-form')
        </div>
    </div>
</x-app-layout>
