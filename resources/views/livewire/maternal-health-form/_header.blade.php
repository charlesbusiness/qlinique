<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Maternal Health Assessment</h5>
        @if ($isDraft)
            <span class="badge bg-info">Step {{ $step }} of {{ count($stepLabels) }}</span>
        @endif
    </div>
    @if ($isDraft)
        <div class="progress" style="height: 6px;">
            <div class="progress-bar" style="width: {{ ($step / count($stepLabels)) * 100 }}%"></div>
        </div>
        <div class="d-none d-md-flex justify-content-between mt-1">
            @foreach ($stepLabels as $num => $label)
                <small class="{{ $num === $step ? 'text-primary fw-bold' : ($num < $step ? 'text-success' : 'text-muted') }}">{{ $label }}</small>
            @endforeach
        </div>
    @endif
</div>
