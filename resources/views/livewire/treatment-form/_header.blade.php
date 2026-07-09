@if ($isDraft && $draftId)
    <div class="mb-2 d-flex justify-content-end align-items-center">
        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#discardDraftModal">Discard Draft</button>
    </div>
@endif

@if ($step > 0)
    <div class="mb-4">
        <div class="d-flex gap-2 flex-wrap">
            @foreach ($stepLabels as $num => $label)
                <span class="badge {{ $step >= $num ? 'bg-primary' : 'bg-secondary' }} fs-6 px-3 py-2 {{ $step === $num ? '' : 'd-none d-md-inline' }}">
                   {{ $label }}
                </span>
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
