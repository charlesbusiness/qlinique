<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Dashboard') }}</h2>
    </x-slot>

    <div class="row g-3 mb-4">
        @if (isset($stats['total_patients']))
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3 class="card-title mb-0">{{ $stats['total_patients'] }}</h3>
                    <small>Total Patients</small>
                </div>
            </div>
        </div>
        @endif
        @if (isset($stats['active_treatments']))
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h3 class="card-title mb-0">{{ $stats['active_treatments'] }}</h3>
                    <small>Active Treatments</small>
                </div>
            </div>
        </div>
        @endif
        @if (isset($stats['today_treatments']))
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h3 class="card-title mb-0">{{ $stats['today_treatments'] }}</h3>
                    <small>Today's Visits</small>
                </div>
            </div>
        </div>
        @endif
        @if (isset($stats['pending_revenue']))
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h3 class="card-title mb-0">{{ number_format($stats['pending_revenue'], 0) }}</h3>
                    <small>Outstanding ({{ $stats['pending_invoices'] }} invoices)</small>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="row g-3 mb-4">
        @if ($compliance)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <strong>Compliance Overview</strong>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-muted">{{ $compliance['total_active'] }}</h4>
                            <small>Active</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-success">{{ $compliance['compliant'] }}</h4>
                            <small>Compliant</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-danger">{{ $compliance['non_compliant'] }}</h4>
                            <small>Non-Compliant</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>Quick Links</strong></div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @if (Auth::user()->hasPermission('patients.create'))
                        <a href="{{ route('patients.create') }}" class="btn btn-outline-primary">Register Patient</a>
                        @endif
                        @if (Auth::user()->hasPermission('treatments.create'))
                        <a href="{{ route('treatments.create') }}" class="btn btn-outline-warning">New Treatment</a>
                        @endif
                        @if (Auth::user()->hasPermission('antenatal.create'))
                        <a href="{{ route('antenatal.create') }}" class="btn btn-outline-info">Antenatal Record</a>
                        @endif
                        @if (Auth::user()->hasPermission('finance.invoices.create'))
                        <a href="{{ route('finance.create-invoice') }}" class="btn btn-outline-success">Generate Invoice</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @if ($recentPatients->isNotEmpty())
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <strong>Recent Patients</strong>
                    <a href="{{ route('patients.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr><th>File No.</th><th>Name</th><th>Type</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($recentPatients as $p)
                                <tr>
                                    <td><span class="badge bg-secondary">{{ $p->file?->file_number ?? '—' }}</span></td>
                                    <td>{{ $p->name }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($p->file?->type ?? '—') }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if ($recentTreatments->isNotEmpty())
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <strong>Recent Treatments</strong>
                    <a href="{{ route('treatments.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr><th>Patient</th><th>Category</th><th>Date</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($recentTreatments as $t)
                                <tr>
                                    <td>{{ $t->patient->name ?? '—' }}</td>
                                    <td><span class="badge bg-warning">{{ ucfirst($t->category) }}</span></td>
                                    <td>{{ $t->visit_date->format('d M') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
