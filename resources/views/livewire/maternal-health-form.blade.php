<div>
    @include('livewire.maternal-health-form._header')

    <form wire:submit="nextStep">
        @if ($step === 1)
            @include('livewire.maternal-health-form._step1_patient_summary')
        @elseif ($step === 2)
            @include('livewire.maternal-health-form._step2_pregnancy')
        @elseif ($step === 3)
            @include('livewire.maternal-health-form._step3_medical_history')
        @elseif ($step === 4)
            @include('livewire.maternal-health-form._step4_vitals_rme')
        @elseif ($step === 5)
            @include('livewire.maternal-health-form._step5_physical_exam')
        @elseif ($step === 6)
            @include('livewire.maternal-health-form._step6_diagnosis_treatment')
        @elseif ($step === 7)
            @include('livewire.maternal-health-form._step7_billing_consent')
        @endif

        @include('livewire.maternal-health-form._navigation')
    </form>

    @livewire('modals.manage-schedule-modal', key('schedule-modal-form'))

    @script
    <script>
        window.addEventListener('set-physician-signature', (e) => {
            $wire.set('attending_physician_signature', e.detail.value);
        });
    </script>
    @endscript
</div>
