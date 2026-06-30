<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Family & Corporate Files') }}</h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            @livewire('family-file-index')
        </div>
    </div>
</x-app-layout>
