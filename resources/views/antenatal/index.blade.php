<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Antenatal Records') }}</h2>
            @if (Auth::user()->hasPermission('antenatal.create'))
            <a href="{{ route('antenatal.create') }}" class="btn btn-primary">+ New Record</a>
            @endif
        </div>
    </x-slot>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('status') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Patient</th>
                        <th>EDD</th>
                        <th>Gestation (wks)</th>
                        <th>Risk Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>{{ $record->patient->name ?? '—' }}</td>
                            <td>{{ $record->edd?->format('d M Y') ?? '—' }}</td>
                            <td>{{ $record->gestation_weeks ?? '—' }}</td>
                            <td>
                                @php $color = match($record->risk_level) { 'high' => 'danger', 'medium' => 'warning', default => 'success' }; @endphp
                                <span class="badge bg-{{ $color }}">{{ ucfirst($record->risk_level) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('antenatal.show', $record) }}" class="btn btn-sm btn-outline-primary">View</a>
                                @if (Auth::user()->hasPermission('antenatal.partograph'))
                                <a href="{{ route('antenatal.partograph', $record) }}" class="btn btn-sm btn-outline-info">Partograph</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No antenatal records.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
        @if ($records->hasPages())
            <div class="card-footer">{{ $records->links() }}</div>
        @endif
    </div>
</x-app-layout>
