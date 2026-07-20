<div class="d-flex justify-content-between mt-4">
    <div>
        @if ($step > 1)
            <button type="button" class="btn btn-outline-secondary" wire:click="prevStep">Previous</button>
        @endif
    </div>
    <div>
        @if ($step < 7)
            <button type="submit" class="btn btn-primary">Save & Continue</button>
        @else
            <button type="submit" class="btn btn-success">{{ $isEditing ? 'Update Record' : 'Submit Record' }}</button>
        @endif
    </div>
</div>
