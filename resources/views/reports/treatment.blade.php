<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Assessment Report') }}</h2>
    </x-slot>

    <form method="GET" class="d-flex gap-2 mb-4">
        <input type="date" name="from" class="form-control w-auto" value="{{ $from->format('Y-m-d') }}">
        <input type="date" name="to" class="form-control w-auto" value="{{ $to->format('Y-m-d') }}">
        <button class="btn btn-primary">Filter</button>
    </form>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card bg-light"><div class="card-body text-center">
                <h3>{{ $report['total'] }}</h3>
                <small>Total</small>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light"><div class="card-body text-center">
                <h3>{{ $report['completed'] }}</h3>
                <small>Completed</small>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light"><div class="card-body text-center">
                <h3>{{ $report['total'] - $report['completed'] }}</h3>
                <small>Active</small>
            </div></div>
        </div>
    </div>

    <h5 class="mt-4 mb-3">By Category</h5>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card bg-light"><div class="card-body text-center">
                <h3>{{ $report['by_category']['checkup'] }}</h3>
                <small>Check-up</small>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light"><div class="card-body text-center">
                <h3>{{ $report['by_category']['treatment'] }}</h3>
                <small>Treatment</small>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light"><div class="card-body text-center">
                <h3>{{ $report['by_category']['maternal_health'] }}</h3>
                <small>Maternal Health Care</small>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light"><div class="card-body text-center">
                <h3>{{ $report['by_category']['enrollment_palliative'] }}</h3>
                <small>Enrollment & Palliative Care</small>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light"><div class="card-body text-center">
                <h3 class="text-danger">{{ $report['by_category']['emergency_accident'] }}</h3>
                <small>Emergency & Accident</small>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light"><div class="card-body text-center">
                <h3>{{ $report['by_category']['consultancy'] }}</h3>
                <small>Consultancy / Counseling</small>
            </div></div>
        </div>
    </div>
</x-app-layout>
