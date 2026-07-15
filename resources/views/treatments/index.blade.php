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
            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                <form method="GET" class="d-flex gap-2 flex-grow-1" style="max-width: 400px;">
                    @if (request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                    @if (request('pending')) <input type="hidden" name="pending" value="{{ request('pending') }}"> @endif
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name, phone or file number..." value="{{ request('search') }}">
                    <button class="btn btn-sm btn-outline-primary">Search</button>
                </form>
            </div>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link {{ !request('status') && !request('pending') ? 'active' : '' }}" href="{{ route('treatments.index') }}">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') === 'active' && !request('pending') ? 'active' : '' }}" href="{{ route('treatments.index', ['status' => 'active']) }}">Active</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('publish_status') === 'draft' ? 'active' : '' }}" href="{{ route('treatments.index', ['publish_status' => 'draft']) }}">Drafts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('pending') === 'today' ? 'active' : '' }}" href="{{ route('treatments.index', ['pending' => 'today']) }}">Pending Today</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('pending') === 'week' ? 'active' : '' }}" href="{{ route('treatments.index', ['pending' => 'week']) }}">Pending This Week</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('pending') === 'month' ? 'active' : '' }}" href="{{ route('treatments.index', ['pending' => 'month']) }}">Pending This Month</a>
                </li>
            </ul>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Category</th>
                        <th>Visit Date</th>
                        <th>Diagnosis</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($treatments as $treatment)
                        <tr>
                            <td>{{ $treatment->id }}</td>
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
