<div>
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">Documents</h6>
        <button type="button" class="btn btn-sm btn-outline-primary" wire:click="toggleForm">
            {{ $showUploadForm ? 'Cancel' : '+ Upload Document' }}
        </button>
    </div>

    @if ($showUploadForm)
        <div class="card bg-light mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <input type="file" class="form-control @error('file') is-invalid @enderror" x-on:change="$wire.upload('file', $event.target.files[0])">
                    @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <textarea class="form-control @error('description') is-invalid @enderror" wire:model="description" rows="2" placeholder="Description (optional)"></textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button type="button" class="btn btn-sm btn-primary" wire:click="upload" wire:loading.attr="disabled">
                    <span wire:loading wire:target="upload">Uploading...</span>
                    <span wire:loading.remove wire:target="upload">Upload</span>
                </button>
            </div>
        </div>
    @endif

    @if ($documents->isEmpty())
        <p class="text-muted small mb-0">No documents uploaded.</p>
    @else
        <div class="list-group">
            @foreach ($documents as $doc)
                @if ($doc)
                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <div>
                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-decoration-none">
                                {{ $doc->name }}
                            </a>
                            @if ($doc->description)
                                <br><small class="text-muted">{{ $doc->description }}</small>
                            @endif
                            <br><small class="text-muted">
                                @if ($doc->file_size)
                                    {{ round($doc->file_size / 1024, 1) }} KB
                                @endif
                                @if ($doc->uploadedBy)
                                    &middot; by {{ $doc->uploadedBy->name }}
                                @endif
                            </small>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $doc->id }})"
                        onclick="return confirm('Delete {{ $doc->name }}?')">Delete</button>
                </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
