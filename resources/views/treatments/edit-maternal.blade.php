<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">Edit Maternal Health Record — {{ $treatment->patient->name }}</h2>
            <a href="{{ route('treatments.show', $treatment) }}" class="btn btn-outline-primary btn-sm">Back</a>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-body">
            @livewire('maternal-health-form', ['recordId' => $record->id])
        </div>
    </div>
</x-app-layout>
