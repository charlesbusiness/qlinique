<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">Antenatal — {{ $antenatal->patient->name }}</h2>
            @if (Auth::user()->hasPermission('antenatal.partograph'))
            <a href="{{ route('antenatal.partograph', $antenatal) }}" class="btn btn-outline-info btn-sm">Partograph</a>
            @endif
        </div>
    </x-slot>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><strong>Pregnancy Info</strong></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Patient:</strong> {{ $antenatal->patient->name }} ({{ $antenatal->patient->file_number }})</p>
                    <p class="mb-1"><strong>EDD:</strong> {{ $antenatal->edd?->format('d M Y') ?? '—' }}</p>
                    <p class="mb-1"><strong>Gestation:</strong> {{ $antenatal->gestation_weeks ?? '—' }} weeks</p>
                    <p class="mb-0"><strong>Risk Level:</strong>
                        @php $color = match($antenatal->risk_level) { 'high' => 'danger', 'medium' => 'warning', default => 'success' }; @endphp
                        <span class="badge bg-{{ $color }}">{{ ucfirst($antenatal->risk_level) }}</span>
                    </p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><strong>Obstetric History</strong></div>
                <div class="card-body">
                    <p class="mb-0">{{ $antenatal->obstetric_history ?? 'No history recorded.' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><strong>Partograph Entries</strong></div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date/Time</th>
                                <th>Cervical</th>
                                <th>FHR</th>
                                <th>BP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($antenatal->partographs as $p)
                                <tr>
                                    <td>{{ $p->recorded_at->format('d M H:i') }}</td>
                                    <td>{{ $p->cervical_dilation ?? '—' }} cm</td>
                                    <td>{{ $p->fetal_heart_rate ?? '—' }}</td>
                                    <td>{{ $p->blood_pressure_systolic }}/{{ $p->blood_pressure_diastolic }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">No partograph entries.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
