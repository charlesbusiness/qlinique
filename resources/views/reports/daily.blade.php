<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Daily Report') }} — {{ $date->format('d M Y') }}</h2>
    </x-slot>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-light"><div class="card-body text-center">
                <h3>{{ $report['new_patients'] }}</h3>
                <small>New Patients</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light"><div class="card-body text-center">
                <h3>{{ $report['treatments'] }}</h3>
                <small>Treatments</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light"><div class="card-body text-center">
                <h3 class="text-danger">{{ $report['emergencies'] }}</h3>
                <small>Emergencies</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light"><div class="card-body text-center">
                <h3 class="text-success">{{ number_format($report['revenue'], 2) }}</h3>
                <small>Revenue</small>
            </div></div>
        </div>
    </div>

    <form method="GET" class="d-flex gap-2">
        <input type="date" name="date" class="form-control w-auto" value="{{ $date->format('Y-m-d') }}">
        <button class="btn btn-primary">View</button>
    </form>
</x-app-layout>
