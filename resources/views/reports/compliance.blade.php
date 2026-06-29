<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Compliance Report') }}</h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <p class="text-muted">Compliance tracking is available per-treatment from the Treatment Compliance page.</p>
            <p>Visit <a href="{{ route('treatments.index', ['status' => 'active']) }}">Active Treatments</a> to manage compliance for individual treatment charts.</p>
        </div>
    </div>
</x-app-layout>
