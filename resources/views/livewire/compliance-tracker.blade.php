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
        <p class="text-muted">Expected sessions: {{ $expectedSessions }} out of 7 days</p>
    @endif

    <div class="d-flex gap-2 flex-wrap">
        @foreach ($days as $day)
            <div class="card text-center" style="min-width: 100px;">
                <div class="card-header small">Day {{ $day['day'] }}</div>
                <div class="card-body p-2">
                    <small class="text-muted">{{ $day['date']->format('d M') }}</small>
                    <div class="mt-2">
                        @if ($day['status'] === 'attended')
                            <span class="badge bg-success">Attended</span>
                        @elseif ($day['status'] === 'missed')
                            <span class="badge bg-danger">Missed</span>
                        @else
                            <span class="badge bg-secondary">Pending</span>
                        @endif
                    </div>
                    <div class="mt-2 d-flex gap-1 justify-content-center">
                        <button class="btn btn-sm btn-outline-success" wire:click="markAttended({{ $day['day'] - 1 }})">&#10003;</button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="markMissed({{ $day['day'] - 1 }})">&#10007;</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
