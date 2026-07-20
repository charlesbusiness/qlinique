<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Assessment Charts') }}</h2>
            @if (Auth::user()->hasPermission('treatments.create'))
            <a href="{{ route('treatments.create') }}" class="btn btn-primary">+ New Assessment</a>
            @endif
        </div>
    </x-slot>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('status') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row g-2 align-items-center">
                {{-- Search --}}
                <div class="col-12 col-md-5">
                    <form method="GET" class="d-flex gap-2">
                        @if (request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                        @if (request('pending')) <input type="hidden" name="pending" value="{{ request('pending') }}"> @endif
                        @if (request('publish_status')) <input type="hidden" name="publish_status" value="{{ request('publish_status') }}"> @endif
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name, phone or file number..." value="{{ request('search') }}">
                        <button class="btn btn-sm btn-outline-primary">Search</button>
                    </form>
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
                            <li><a class="dropdown-item {{ !$hasActiveStatus ? 'active' : '' }}" href="{{ route('treatments.index', array_filter(['search' => request('search')])) }}">All</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item {{ request('status') === 'active' && !request('pending') ? 'active' : '' }}" href="{{ route('treatments.index', array_filter(['status' => 'active', 'search' => request('search')])) }}">Active</a></li>
                            <li><a class="dropdown-item {{ request('publish_status') === 'draft' ? 'active' : '' }}" href="{{ route('treatments.index', array_filter(['publish_status' => 'draft', 'search' => request('search')])) }}">Drafts</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item {{ request('pending') === 'today' ? 'active' : '' }}" href="{{ route('treatments.index', array_filter(['pending' => 'today', 'search' => request('search')])) }}">Pending Today</a></li>
                            <li><a class="dropdown-item {{ request('pending') === 'week' ? 'active' : '' }}" href="{{ route('treatments.index', array_filter(['pending' => 'week', 'search' => request('search')])) }}">Pending This Week</a></li>
                            <li><a class="dropdown-item {{ request('pending') === 'month' ? 'active' : '' }}" href="{{ route('treatments.index', array_filter(['pending' => 'month', 'search' => request('search')])) }}">Pending This Month</a></li>
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
                        <th>Category</th>
                        <th>Visit Date</th>
                        <th>Diagnosis</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($treatments as $index => $treatment)
                        <tr>
                            <td>{{ $treatments->firstItem() + $index }}</td>
                            <td>{{ $treatment->patient->name ?? '—' }}</td>
                            <td>
                                <span class="badge bg-warning">{{ \App\Enums\TreatmentCategory::tryFrom($treatment->category)?->label() ?? ucfirst($treatment->category) }}</span>
                                @if ($treatment->category === 'other' && $treatment->other_category)
                                    <br><small class="text-muted">{{ $treatment->other_category }}</small>
                                @elseif ($treatment->sub_category)
                                    <br><small class="text-muted">{{ (\App\Livewire\TreatmentForm::assessmentSubOptions($treatment->category))[$treatment->sub_category] ?? $treatment->sub_category }}</small>
                                @endif
                            </td>
                            <td>{{ $treatment->visit_date->format('d M Y') }}</td>
                            <td>{{ Str::limit($treatment->primary_diagnosis, 40) ?? '—' }}</td>
                            <td>
                                <a href="{{ route('treatments.show', $treatment) }}" class="btn btn-sm btn-outline-primary">View</a>
                                @if (!$treatment->is_completed && Auth::user()->hasPermission('treatments.edit'))
                                <a href="{{ route('treatments.edit', $treatment) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No treatment records.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        @if ($treatments->hasPages())
            <div class="card-footer">{{ $treatments->links() }}</div>
        @endif
    </div>
</x-app-layout>
