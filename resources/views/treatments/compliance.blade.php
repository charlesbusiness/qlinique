<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">Compliance — {{ $treatment->patient->name }}</h2>
            <a href="{{ route('treatments.show', $treatment) }}" class="btn btn-outline-primary btn-sm">Back to Chart</a>
        </div>
    </x-slot>

    <div class="card mb-4">
        <div class="card-header">
            <strong>Treatment:</strong> {{ $treatment->primary_diagnosis ?? 'N/A' }} —
            <span class="badge bg-warning">{{ ucfirst($treatment->category) }}</span>
            ({{ $treatment->visit_date->format('d M Y') }})
        </div>
        <div class="card-body">
            @livewire('compliance-tracker', ['treatment' => $treatment], key($treatment->id))
        </div>
    </div>
</x-app-layout>
