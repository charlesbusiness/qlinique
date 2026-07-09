@if ($step > 0)
    <div class="d-flex justify-content-between mt-4">
        @if ($step > 1)
            <button type="button" class="btn btn-outline-secondary" wire:click="prevStep">Previous</button>
        @else
            <div></div>
        @endif

        @if ($step < 7)
            <button type="submit" class="btn btn-primary">
                @if ($step === 0) Continue @else Save & Continue @endif
            </button>
        @else
            <button type="submit" class="btn btn-success">{{ $isEditing ? 'Update Treatment Chart' : 'Publish Treatment Chart' }}</button>
        @endif
    </div>
@endif
