<button type="button" class="btn btn-link btn-sm p-0 mb-3 text-decoration-none"
        wire:click="goBackToAntenatalOptions">
    &larr; Back to Antenatal Care options
</button>

<h5 class="mb-3">
    @if ($maternalPatientSelectionType === 'revisit')
        Re-visit: Select Patient
    @else
        ANC First Contact: Select Patient
    @endif
</h5>

<select class="form-select @error('antenatalPatientId') is-invalid @enderror @error('revisitPatientId') is-invalid @enderror"
        wire:model.live="{{ $maternalPatientSelectionType === 'revisit' ? 'revisitPatientId' : 'antenatalPatientId' }}">
    <option value="">Search antenatal patient...</option>
    @foreach ($antenatalPatients as $p)
        <option value="{{ $p->id }}">{{ $p->file?->file_number ?? 'N/A' }} — {{ $p->name }}</option>
    @endforeach
</select>

@error('antenatalPatientId') <div class="invalid-feedback">{{ $message }}</div> @enderror
@error('revisitPatientId') <div class="invalid-feedback">{{ $message }}</div> @enderror

@if (($maternalPatientSelectionType === 'revisit' && $revisitPatientId) ||
      ($maternalPatientSelectionType === 'first_contact' && $antenatalPatientId))
    <div class="mt-3">
        <button type="button" class="btn btn-primary" wire:click="goToMaternalForm">
            Continue to Assessment
        </button>
    </div>
@endif


<!-- 2ND DOSE OF T.D IMMUNIZATION {tetanus & diptheria detoxoids}
ROUTE OF ADMINISTRATION	NAME OF MEDICATION	STRENGTH	DOSAGE REGIMEN	LENGTH	AMOUNT
										 -->
