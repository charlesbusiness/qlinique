<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Treatment Charts') }}</h2>
            @if (Auth::user()->hasPermission('treatments.create'))
            <a href="{{ route('treatments.create') }}" class="btn btn-primary">+ New Treatment</a>
            @endif
        </div>
    </x-slot>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('status') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link {{ request('status') !== 'active' ? 'active' : '' }}" href="{{ route('treatments.index') }}">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') === 'active' ? 'active' : '' }}" href="{{ route('treatments.index', ['status' => 'active']) }}">Active</a>
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
                        <th>Status</th>
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
                                @endif
                            </td>
                            <td>{{ $treatment->visit_date->format('d M Y') }}</td>
                            <td>{{ Str::limit($treatment->primary_diagnosis, 40) ?? '—' }}</td>
                            <td>
                                @if ($treatment->is_completed)
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-primary">Active</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('treatments.show', $treatment) }}" class="btn btn-sm btn-outline-primary">View</a>
                                @if (Auth::user()->hasPermission('treatments.compliance'))
                                <a href="{{ route('treatments.compliance', $treatment) }}" class="btn btn-sm btn-outline-info">Compliance</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No treatment records.</td></tr>
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
