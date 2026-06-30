<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Patient Files') }}</h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            @livewire('patient-file-index')
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('open-edit-modal', () => {
                new bootstrap.Modal('#editFileModal').show();
            });
            Livewire.on('close-modal', ({ modalId }) => {
                bootstrap.Modal.getInstance(document.getElementById(modalId))?.hide();
            });
        });
    </script>
    @endpush
</x-app-layout>
