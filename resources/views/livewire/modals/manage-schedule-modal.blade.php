<div>
    @if ($isInline)
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><i class="bi bi-calendar-event"></i> Visit Schedule</strong>
                <small class="text-muted">
                    {{ count($schedule_visits) }} visit{{ count($schedule_visits) !== 1 ? 's' : '' }}
                    — {{ count(array_filter($schedule_visits, fn($v) => $v['status'] === 'completed')) }} completed
                </small>
            </div>
            <div class="card-body">
                @if ($visitType === 'revisit')
                    <p class="text-muted small mb-3">
                        <i class="bi bi-info-circle"></i>
                        Current dose: <strong>{{ self::ordinal($this->currentDoseNumber) }}</strong> (IPT & TD)
                    </p>
                @endif

                @foreach ($schedule_visits as $i => $visit)
                    @php $visitNum = $i + 1; @endphp

                    @if ($i === 0)
                        {{-- Visit #1: Read-only first contact --}}
                        <div class="border rounded p-3 mb-3 bg-success bg-opacity-10 border-success border-opacity-25">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-1 text-center">
                                    <span class="badge bg-success fs-6">{{ $visitNum }}</span>
                                </div>
                                <div class="col-md-5">
                                    <span class="fw-semibold">{{ $visit['label'] }}</span>
                                </div>
                                <div class="col-md-3">
                                    <span class="text-muted">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $visit['date'] ? \Carbon\Carbon::parse($visit['date'])->format('d M Y') : '—' }}
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Completed</span>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Visit #2+: Editable --}}
                        <div class="border rounded p-3 mb-3 bg-light">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-1 text-center">
                                    <span class="badge bg-primary fs-6">{{ $visitNum }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Label</label>
                                    <input type="text" class="form-control form-control-sm @error('schedule_visits.{{ $i }}.label') is-invalid @enderror"
                                        wire:model="schedule_visits.{{ $i }}.label"
                                        placeholder="e.g. {{ self::ordinal($visitNum) }} Antenatal Visit">
                                    @error('schedule_visits.{{ $i }}.label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">Duration (Quick Select)</label>
                                    <select class="form-select form-select-sm"
                                        wire:model.live="schedule_visits.{{ $i }}.duration"
                                        wire:change="calculateDate({{ $i }})">
                                        <option value="">Manual Entry</option>
                                        @foreach (self::$nextVisitDurationOptions as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">Scheduled Date</label>
                                    <input type="date" class="form-control form-control-sm @error('schedule_visits.{{ $i }}.date') is-invalid @enderror"
                                        wire:model="schedule_visits.{{ $i }}.date"
                                        {{ !empty($visit['duration']) ? 'readonly' : '' }}>
                                    @error('schedule_visits.{{ $i }}.date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeVisit({{ $i }})" title="Remove">&times;</button>
                                </div>
                            </div>
                            @if (($visit['status'] ?? '') === 'completed')
                                <div class="mt-1">
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Completed</span>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach

                <button type="button" class="btn btn-outline-primary btn-sm" wire:click="addVisit">
                    <i class="bi bi-plus-circle"></i> Add Visit
                </button>
            </div>
            @if (count($schedule_visits) > 1)
                <div class="card-footer">
                    <button type="button" class="btn btn-primary btn-sm" wire:click="saveSchedule">
                        <i class="bi bi-check-lg"></i> Save Schedule
                    </button>
                </div>
            @endif
        </div>
    @else
        @if ($showModal)
            <div wire:ignore.self class="modal fade show" style="display: block;" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-calendar-event"></i> Visit Schedule
                            </h5>
                            <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            @if ($visitType === 'first_contact')
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-info-circle"></i>
                                    Visit #1 is the current visit. Define the upcoming visits below.
                                    The visit number is used as the dose number for IPT and TD immunizations.
                                </p>
                            @elseif ($visitType === 'revisit')
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-info-circle"></i>
                                    Current dose: <strong>{{ self::ordinal($this->currentDoseNumber) }}</strong> (IPT & TD)
                                    — you can add more visits below.
                                </p>
                            @endif

                            @if (empty($schedule_visits))
                                <div class="alert alert-light border text-muted small mb-3">
                                    <i class="bi bi-calendar-plus"></i> No visits scheduled yet. Add a visit below to get started.
                                </div>
                            @endif

                            @foreach ($schedule_visits as $i => $visit)
                                @php $visitNum = $i + 1; @endphp
                                <div class="border rounded p-3 mb-3 bg-light">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-1 text-center">
                                            <span class="badge bg-primary fs-6">{{ $visitNum }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Label</label>
                                            <input type="text" class="form-control form-control-sm @error('schedule_visits.{{ $i }}.label') is-invalid @enderror"
                                                wire:model="schedule_visits.{{ $i }}.label"
                                                placeholder="e.g. {{ self::ordinal($visitNum) }} Antenatal Visit">
                                            @error('schedule_visits.{{ $i }}.label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Duration (Quick Select)</label>
                                            <select class="form-select form-select-sm"
                                                wire:model.live="schedule_visits.{{ $i }}.duration"
                                                wire:change="calculateDate({{ $i }})">
                                                <option value="">Manual Entry</option>
                                                @foreach (self::$nextVisitDurationOptions as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Scheduled Date</label>
                                            <input type="date" class="form-control form-control-sm @error('schedule_visits.{{ $i }}.date') is-invalid @enderror"
                                                wire:model="schedule_visits.{{ $i }}.date"
                                                {{ !empty($visit['duration']) ? 'readonly' : '' }}>
                                            @error('schedule_visits.{{ $i }}.date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeVisit({{ $i }})" title="Remove">&times;</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <button type="button" class="btn btn-outline-primary btn-sm" wire:click="addVisit">
                                <i class="bi bi-plus-circle"></i> Add Visit
                            </button>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" wire:click="closeModal">Close</button>

                            @if (count($schedule_visits) > 0)
                                <button type="button" class="btn btn-primary" wire:click="saveSchedule">
                                    <i class="bi bi-check-lg"></i> Save Schedule
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
        @endif
    @endif
</div>
