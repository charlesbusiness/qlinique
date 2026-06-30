<div class="position-relative" wire:ignore.self>
    <input type="text"
        class="form-control"
        placeholder="Search by name or file number..."
        wire:model.live.debounce.300ms="query"
        wire:focus="showDropdown = true"
        wire:blur="showDropdown = false"
        autocomplete="off">

    @if ($showDropdown && count($results) > 0)
        <ul class="list-group position-absolute w-100 shadow-sm" style="z-index: 1000;">
            @foreach ($results as $result)
                <li class="list-group-item list-group-item-action" wire:click="selectPatient({{ $result->id }})" style="cursor: pointer;">
                    <strong>{{ $result->file?->file_number ?? '—' }}</strong> — {{ $result->name }}
                </li>
            @endforeach
        </ul>
    @endif
</div>
