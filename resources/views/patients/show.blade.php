<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">{{ $patient->name }}</h2>
            <div>
                @if (Auth::user()->hasPermission('patients.edit'))
                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                @endif
                <a href="{{ route('patients.index') }}" class="btn btn-outline-primary btn-sm">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if ($patient->photo_path)
                        <img src="{{ asset('storage/' . $patient->photo_path) }}" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px;">
                            <span class="fs-1 text-white">{{ strtoupper(substr($patient->name, 0, 2)) }}</span>
                        </div>
                    @endif
                    <h5>{{ $patient->name }}</h5>
                    <span class="badge bg-secondary">{{ $patient->file_number }}</span>
                    <span class="badge bg-info">{{ ucfirst($patient->account_type) }}</span>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><strong>Account Info</strong></div>
                <div class="card-body">
                    @if ($patient->accountHolder)
                        <p class="mb-1"><strong>Account Holder:</strong> {{ $patient->accountHolder->name }}</p>
                    @endif
                    @if ($patient->dependants->count() > 0)
                        <p class="mb-1"><strong>Dependants:</strong></p>
                        <ul>
                            @foreach ($patient->dependants as $dep)
                                <li>{{ $dep->name }} ({{ $dep->file_number }})</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header"><strong>Bio Data</strong></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2"><strong>Gender:</strong> {{ ucfirst($patient->gender) }}</div>
                        <div class="col-md-6 mb-2"><strong>DOB:</strong> {{ $patient->date_of_birth->format('d M Y') }}</div>
                        <div class="col-md-6 mb-2"><strong>Phone:</strong> {{ $patient->phone ?? '—' }}</div>
                        <div class="col-md-6 mb-2"><strong>Email:</strong> {{ $patient->email ?? '—' }}</div>
                        <div class="col-md-6 mb-2"><strong>Occupation:</strong> {{ $patient->occupation ?? '—' }}</div>
                        <div class="col-md-6 mb-2"><strong>Marital Status:</strong> {{ $patient->marital_status ?? '—' }}</div>
                        <div class="col-12 mb-2"><strong>Address:</strong> {{ $patient->address ?? '—' }}</div>
                        <div class="col-md-6 mb-2"><strong>Religion:</strong> {{ $patient->religion ?? '—' }}</div>
                        <div class="col-md-6 mb-2"><strong>Denomination:</strong> {{ $patient->denomination ?? '—' }}</div>
                    </div>
                </div>
            </div>

            @if ($patient->next_of_kin)
                <div class="card mb-4">
                    <div class="card-header"><strong>Next of Kin</strong></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-2"><strong>Name:</strong> {{ $patient->next_of_kin['name'] ?? '—' }}</div>
                            <div class="col-md-4 mb-2"><strong>Relationship:</strong> {{ $patient->next_of_kin['relationship'] ?? '—' }}</div>
                            <div class="col-md-4 mb-2"><strong>Phone:</strong> {{ $patient->next_of_kin['phone'] ?? '—' }}</div>
                            <div class="col-12 mb-2"><strong>Address:</strong> {{ $patient->next_of_kin['address'] ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <strong>Recent Treatment Charts</strong>
                    @if (Auth::user()->hasPermission('treatments.create'))
                    <a href="{{ route('treatments.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-primary">+ New Treatment</a>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Diagnosis</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($patient->treatmentCharts->take(5) as $chart)
                                <tr>
                                    <td>{{ $chart->visit_date->format('d M Y') }}</td>
                                    <td><span class="badge bg-warning">{{ ucfirst($chart->category) }}</span></td>
                                    <td>{{ $chart->primary_diagnosis ?? '—' }}</td>
                                    <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">No treatment records.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
