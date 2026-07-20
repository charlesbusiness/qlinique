@if ($isDraft && $draftId)
    <div class="mb-2 d-flex justify-content-end align-items-center">
        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#discardDraftModal">Discard Draft</button>
    </div>
@endif

@if ($step > 0)
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">New Treatment</h5>
            <span class="badge bg-info">Step {{ $step }} of {{ count($stepLabels) }}</span>
        </div>
        <div class="progress" style="height: 6px;">
            <div class="progress-bar" style="width: {{ ($step / count($stepLabels)) * 100 }}%"></div>
        </div>
        <div class="d-none d-md-flex justify-content-between mt-1">
            @foreach ($stepLabels as $num => $label)
                <small class="{{ $num === $step ? 'text-primary fw-bold' : ($num < $step ? 'text-success' : 'text-muted') }}">{{ $label }}</small>
            @endforeach
        </div>
    </div>
@endif

<div wire:ignore.self class="modal fade" id="discardDraftModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Discard Draft</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Are you sure you want to discard this draft and start fresh?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" wire:click="discardDraft" data-bs-dismiss="modal">Discard</button>
            </div>
        </div>
    </div>
</div>


