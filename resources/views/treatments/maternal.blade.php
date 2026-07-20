<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Maternal Health Assessments') }}</h2>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('status') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row g-2 align-items-center">
                {{-- Search --}}
                <div class="col-12 col-md-4">
                    <form method="GET" class="d-flex gap-2">
                        @if (request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                        @if (request('pending')) <input type="hidden" name="pending" value="{{ request('pending') }}"> @endif
                        @if (request('publish_status')) <input type="hidden" name="publish_status" value="{{ request('publish_status') }}"> @endif
                        @if (request('sub_category')) <input type="hidden" name="sub_category" value="{{ request('sub_category') }}"> @endif
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name, phone or file number..." value="{{ request('search') }}">
                        <button class="btn btn-sm btn-outline-primary">Search</button>
                    </form>
                </div>

                {{-- Category Dropdown --}}
                @php
                    $currentCategoryLabel = request('sub_category') ? ($maternalSubOptions[request('sub_category')] ?? request('sub_category')) : 'All';
                @endphp
                <div class="col-6 col-md-auto">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle w-100 text-start" data-bs-toggle="dropdown">
                            Category: {{ $currentCategoryLabel }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item {{ !request('sub_category') ? 'active' : '' }}" href="{{ route('treatments.maternal', array_filter(['status' => request('status'), 'pending' => request('pending'), 'publish_status' => request('publish_status'), 'search' => request('search')])) }}">All</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @foreach ($maternalSubOptions as $value => $label)
                            <li><a class="dropdown-item {{ request('sub_category') === $value ? 'active' : '' }}" href="{{ route('treatments.maternal', array_filter(['sub_category' => $value, 'status' => request('status'), 'pending' => request('pending'), 'publish_status' => request('publish_status'), 'search' => request('search')])) }}">{{ $label }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Status Dropdown --}}
                @php
                    $statusFilters = [
                        'status' => request('status'),
                        'pending' => request('pending'),
                        'publish_status' => request('publish_status'),
                    ];
                    $hasActiveStatus = $statusFilters['status'] === 'active' || $statusFilters['pending'] || $statusFilters['publish_status'];
                    $statusLabel = 'All';
                    if ($statusFilters['pending'] === 'today') $statusLabel = 'Pending Today';
                    elseif ($statusFilters['pending'] === 'week') $statusLabel = 'Pending This Week';
                    elseif ($statusFilters['pending'] === 'month') $statusLabel = 'Pending This Month';
                    elseif ($statusFilters['status'] === 'active') $statusLabel = 'Active';
                    elseif ($statusFilters['publish_status'] === 'draft') $statusLabel = 'Drafts';
                @endphp
                <div class="col-6 col-md-auto">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle w-100 text-start" data-bs-toggle="dropdown">
                            Status: {{ $statusLabel }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item {{ !$hasActiveStatus ? 'active' : '' }}" href="{{ route('treatments.maternal', array_filter(['sub_category' => request('sub_category'), 'search' => request('search')])) }}">All</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item {{ request('status') === 'active' && !request('pending') ? 'active' : '' }}" href="{{ route('treatments.maternal', array_filter(['status' => 'active', 'sub_category' => request('sub_category'), 'search' => request('search')])) }}">Active</a></li>
                            <li><a class="dropdown-item {{ request('publish_status') === 'draft' ? 'active' : '' }}" href="{{ route('treatments.maternal', array_filter(['publish_status' => 'draft', 'sub_category' => request('sub_category'), 'search' => request('search')])) }}">Drafts</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item {{ request('pending') === 'today' ? 'active' : '' }}" href="{{ route('treatments.maternal', array_filter(['pending' => 'today', 'sub_category' => request('sub_category'), 'search' => request('search')])) }}">Pending Today</a></li>
                            <li><a class="dropdown-item {{ request('pending') === 'week' ? 'active' : '' }}" href="{{ route('treatments.maternal', array_filter(['pending' => 'week', 'sub_category' => request('sub_category'), 'search' => request('search')])) }}">Pending This Week</a></li>
                            <li><a class="dropdown-item {{ request('pending') === 'month' ? 'active' : '' }}" href="{{ route('treatments.maternal', array_filter(['pending' => 'month', 'sub_category' => request('sub_category'), 'search' => request('search')])) }}">Pending This Month</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>S/N</th>
                        <th>Patient</th>
                        <th>Visit #</th>
                        <th>Category</th>
                        <th>Schedule</th>
                        <th>Visit Date</th>
                        <th>File Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($treatments as $index => $treatment)
                        @php
                            $visitNum = '—';
                            if ($treatment->maternalHealthRecord) {
                                $completedVisit = $treatment->maternalHealthRecord->antenatalVisits()
                                    ->where('status', 'completed')
                                    ->where('completed_at', '>=', $treatment->visit_date->startOfDay())
                                    ->where('completed_at', '<=', $treatment->visit_date->endOfDay())
                                    ->value('visit_number');
                                $visitNum = $completedVisit ? '#' . $completedVisit : '#1';
                            }

                            $visits = $treatment->maternalHealthRecord?->antenatalVisits ?? collect();
                            $completedCount = $visits->where('status', 'completed')->count();
                            $totalCount = $visits->count();
                        @endphp
                        <tr>
                            <td>{{ $treatments->firstItem() + $index }}</td>
                            <td>{{ $treatment->patient->name ?? 'NA' }}</td>
                            <td>
                                @if ($visitNum !== '—')
                                    <span class="badge bg-primary">{{ $visitNum }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ \App\Enums\TreatmentCategory::tryFrom($treatment->category)?->label() ?? ucfirst($treatment->category) }}</span>
                                @if ($treatment->category === 'maternal_health' && $treatment->sub_category)
                                    <br><small class="text-muted">{{ (\App\Livewire\TreatmentComponents\Treatment\WithConstants::assessmentSubOptions('maternal_health'))[$treatment->sub_category] ?? $treatment->sub_category }}</small>
                                @endif
                            </td>
                            <td>
                                @if ($totalCount > 0)
                                    <span class="badge bg-{{ $completedCount === $totalCount ? 'success' : ($completedCount > 0 ? 'warning' : 'secondary') }}">
                                        {{ $completedCount }}/{{ $totalCount }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $treatment->visit_date->format('d M Y') }}</td>
                            <td>{{ $treatment->patient?->file?->file_number ?? 'NA' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('treatments.show', $treatment) }}"><i class="bi bi-eye me-1"></i> View</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><h6 class="dropdown-header">Create Revisit For:</h6></li>
                                        <li>
                                            <a class="dropdown-item text-success"
                                               href="{{ route('treatments.maternal.create', [
                                                   'patient_id' => $treatment->patient_id,
                                                   'sub_option' => 'antenatal_care',
                                                   'start_step' => 4,
                                                   'visit_type' => 'revisit',
                                               ]) }}">
                                                #1 — 1st Antenatal Visit (First Contact)
                                                <i class="bi bi-check-circle-fill text-success ms-1"></i>
                                            </a>
                                        </li>
                                        @foreach ($visits->sortBy('visit_number') as $visit)
                                            <li>
                                                <a class="dropdown-item {{ $visit->status === 'completed' ? 'text-success' : '' }}"
                                                   href="{{ route('treatments.maternal.create', [
                                                       'patient_id' => $treatment->patient_id,
                                                       'sub_option' => 'antenatal_care',
                                                       'start_step' => 4,
                                                       'visit_type' => 'revisit',
                                                       'visit_id' => $visit->id,
                                                   ]) }}">
                                                    #{{ $visit->visit_number }} — {{ $visit->label }}
                                                    @if ($visit->status === 'completed')
                                                        <i class="bi bi-check-circle-fill text-success ms-1"></i>
                                                    @endif
                                                </a>
                                            </li>
                                        @endforeach
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('treatments.maternal.create', [
                                                'patient_id' => $treatment->patient_id,
                                                'sub_option' => 'antenatal_care',
                                                'start_step' => 4,
                                                'visit_type' => 'unscheduled',
                                            ]) }}">
                                                <i class="bi bi-clock-history me-1"></i> Unscheduled Visit
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No maternal health records.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        @if ($treatments->hasPages())
            <div class="card-footer">{{ $treatments->appends(request()->query())->links() }}</div>
        @endif
    </div>
</x-app-layout>