<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">{{ $patientFile->name }} — Members</h2>
            <a href="{{ route('patient-files.index') }}" class="btn btn-outline-primary btn-sm">Back</a>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ $patientFile->file_number }} ({{ ucfirst($patientFile->type) }})</span>
            <a href="{{ route('patients.create', ['file_id' => $patientFile->id]) }}" class="btn btn-sm btn-success">+ Add Patient</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($patientFile->patients as $patient)
                            <tr>
                                <td>
                                    <a href="{{ route('patients.show', $patient) }}">{{ $patient->name }}</a>
                                </td>
                                <td>{{ $patient->phone ?? '—' }}</td>
                                <td>{{ ucfirst($patient->gender) }}</td>
                                <td>
                                    <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No patients linked to this file.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
