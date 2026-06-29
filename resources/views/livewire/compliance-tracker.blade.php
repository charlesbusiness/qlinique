<div>
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h3 class="text-success">{{ $attended }}</h3>
                    <small>Attended</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h3 class="text-danger">{{ $missed }}</h3>
                    <small>Missed</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h3 class="{{ $percentage >= 75 ? 'text-success' : ($percentage >= 50 ? 'text-warning' : 'text-danger') }}">
                        {{ $percentage }}%
                    </h3>
                    <small>Compliance</small>
                </div>
            </div>
        </div>
    </div>

    @if ($expectedSessions > 0)
        <p class="text-muted">
            Expected sessions: <strong>{{ $expectedSessions }}</strong> ({{ $totalSlots }} {{ $label['plural'] }})
        </p>
    @endif

    <div style="max-height: 600px; overflow-y: auto;">
        <div class="d-flex gap-2 flex-wrap">
            @foreach ($entries as $entry)
                <div class="card text-center" style="min-width: 110px;">
                    <div class="card-header small">{{ $entry['label'] }}</div>
                    <div class="card-body p-2">
                        <small class="text-muted">{{ $entry['date_label'] }}</small>
                        <div class="mt-2">
                            @if ($entry['status'] === 'attended')
                                <span class="badge bg-success">Attended</span>
                            @elseif ($entry['status'] === 'missed')
                                <span class="badge bg-danger">Missed</span>
                            @else
                                <span class="badge bg-secondary">Pending</span>
                            @endif
                        </div>
                        @if (!$entry['date']->isFuture())
                            <div class="mt-2 d-flex gap-1 justify-content-center">
                                <button class="btn btn-sm btn-outline-success" wire:click="markAttended({{ $entry['index'] }})">&#10003;</button>
                                <button class="btn btn-sm btn-outline-danger" wire:click="markMissed({{ $entry['index'] }})">&#10007;</button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
