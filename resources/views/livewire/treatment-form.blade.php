<div>
    @include('livewire.treatment-form._header')
    <form wire:submit="nextStep">
        @if ($step === 0)
            @include('livewire.treatment-form._step0_patient_selection')
        @elseif ($step === 1)
            @include('livewire.treatment-form._step1_history')
        @elseif ($step === 2)
            @include('livewire.treatment-form._step2_vitals')
        @elseif ($step === 3)
            @include('livewire.treatment-form._step3_physical_general')
        @elseif ($step === 4)
            @include('livewire.treatment-form._step4_physical_systemic')
        @elseif ($step === 5)
            @include('livewire.treatment-form._step4_investigation')
        @elseif ($step === 6)
            @include('livewire.treatment-form._step6_treatment_plan')
            @include('livewire.treatment-form._step6_consent')
        @elseif ($step === 7)
            @include('livewire.treatment-form._step7_billing')
        @endif
        @include('livewire.treatment-form._navigation')
    </form>
</div>
