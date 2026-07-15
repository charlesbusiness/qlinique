<div>
    @include('livewire.maternal-health-form._header')

    @if ($step === 1 && !$isDraft)
        <div class="text-center py-4">
            <h5>Maternal Health Assessment</h5>
            <p class="text-muted">Patient: <strong>{{ $patient?->name }}</strong> ({{ $patient?->file?->file_number ?? 'N/A' }})</p>
            <p class="text-muted">Sub-category: <strong>{{ str_replace('_', ' ', ucfirst($sub_option)) }}</strong></p>
            <button type="button" class="btn btn-primary mt-3" wire:click="startForm">Begin Assessment</button>
        </div>
    @else
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
    @endif
</div>
