<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Patients') }}</h2>
    </x-slot>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('status') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <form method="GET" class="d-flex gap-2 flex-grow-1 me-3">
                <input type="text" name="search" class="form-control" placeholder="Search by name, file no., or phone..." value="{{ request('search') }}">
                <select name="account_type" class="form-select" style="max-width: 200px;">
                    <option value="">All Accounts</option>
                    @foreach (\App\Enums\AccountType::options() as $value => $label)
                        <option value="{{ $value }}" @selected(request('account_type') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-secondary" type="submit">Search</button>
                @if(request('search') || request('account_type'))
                    <a href="{{ route('patients.index') }}" class="btn btn-outline-danger">Clear</a>
                @endif
            </form>
            @if (Auth::user()->hasPermission('patients.create'))
            <a href="{{ route('patients.create') }}" class="btn btn-primary">+ New Patient</a>
            @endif
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>File No.</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Account Type</th>
                        <th>Patient Type</th>
                        <th>Account</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($patients as $patient)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $patient->file_number }}</span></td>
                            <td>{{ $patient->name }}</td>
                            <td>{{ ucfirst($patient->gender) }}</td>
                            <td>{{ $patient->phone ?? '—' }}</td>
                            <td><span class="badge bg-info">{{ ucfirst($patient->account_type) }}</span></td>
                            <td>{{ $patient->patient_type_label ?? '—' }}</td>
                            <td>{{ $patient->accountHolder?->name ?? '—' }}</td>
                            <td>
                                <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-outline-primary">View</a>
                                @if (Auth::user()->hasPermission('patients.edit'))
                                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No patients found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        @if ($patients->hasPages())
            <div class="card-footer">
                {{ $patients->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
