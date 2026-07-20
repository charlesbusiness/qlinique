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
                    <span class="badge bg-secondary">{{ $patient->file?->file_number ?? '—' }}</span>
                    <span class="badge bg-info">{{ ucfirst($patient->file?->type ?? '—') }}</span>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><strong>Account Info</strong></div>
                <div class="card-body">
                    @if ($patient->file)
                        <p class="mb-1"><strong>{{ ucfirst($patient->file->type) }} File:</strong> {{ $patient->file->name }} ({{ $patient->file->file_number }})</p>
                    @else
                        <p class="mb-1 text-muted">No file assigned</p>
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
                        <div class="col-md-6 mb-2"><strong>Age:</strong> {{ $patient->date_of_birth?->age ?? '—' }}</div>
                        <div class="col-md-6 mb-2"><strong>Phone:</strong> {{ $patient->phone ?? '—' }}</div>
                        <div class="col-md-6 mb-2"><strong>Email:</strong> {{ $patient->email ?? '—' }}</div>
                        <div class="col-md-6 mb-2"><strong>Occupation:</strong> {{ $patient->occupation ?? '—' }}</div>
                        <div class="col-md-6 mb-2"><strong>Marital Status:</strong> {{ $patient->marital_status ?? '—' }}</div>
                        <div class="col-md-6 mb-2"><strong>Blood Group:</strong> {{ $patient->blood_group ?? '—' }}</div>
                        <div class="col-md-6 mb-2"><strong>Genotype:</strong> {{ $patient->genotype ?? '—' }}</div>
                        <div class="col-md-6 mb-2"><strong>Patient Type:</strong> {{ $patient->patient_type_label ?? '—' }}</div>
                        <div class="col-12 mb-2"><strong>Address:</strong> {{ $patient->address ?? '—' }}</div>
                        <div class="col-md-6 mb-2"><strong>Religion:</strong> {{ $patient->religion ?? '—' }}</div>
                    </div>
                </div>
            </div>

            @if ($patient->signature)
                <div class="card mb-4">
                    <div class="card-header"><strong>Signature</strong></div>
                    <div class="card-body">
                        @if ($patient->signature_type === 'typed')
                            <div style="font-family: 'Dancing Script', 'Pacifico', cursive; font-size: 2rem;">
                                {{ $patient->signature }}
                            </div>
                        @elseif ($patient->signature_type === 'drawn' || $patient->signature_type === 'uploaded')
                            <img src="{{ asset('storage/' . $patient->signature) }}" class="border rounded" style="max-height: 80px;">
                        @endif
                    </div>
                </div>
            @endif

            @if ($patient->next_of_kin)
                <div class="card mb-4">
                    <div class="card-header"><strong>Next of Kin</strong></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-2"><strong>Name:</strong> {{ $patient->next_of_kin['name'] ?? '—' }}</div>
                            <div class="col-md-4 mb-2"><strong>Relationship:</strong> {{ $patient->next_of_kin['relationship'] ?? '—' }}</div>
                            <div class="col-md-4 mb-2"><strong>Phone:</strong> {{ $patient->next_of_kin['phone'] ?? '—' }}</div>
                            <div class="col-12 mb-2"><strong>Address:</strong> {{ $patient->next_of_kin['address'] ?? '—' }}</div>
                            @if (!empty($patient->next_of_kin['signature']))
                                <div class="col-12 mt-2">
                                    <strong>Signature:</strong><br>
                                    @if (($patient->next_of_kin['signature_type'] ?? 'typed') === 'drawn' || ($patient->next_of_kin['signature_type'] ?? 'typed') === 'uploaded')
                                        <img src="{{ asset('storage/' . $patient->next_of_kin['signature']) }}" class="border rounded" style="max-height: 60px;">
                                    @else
                                        <span style="font-family: 'Dancing Script', 'Pacifico', cursive; font-size: 1.3rem;">{{ $patient->next_of_kin['signature'] }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-body">
                    @livewire('document-upload', ['documentable' => $patient], key('patient-docs-' . $patient->id))
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <strong>Recent Assessments</strong>
                    @if (Auth::user()->hasPermission('treatments.create'))
                    <a href="{{ route('treatments.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-primary">+ New Assessment</a>
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
