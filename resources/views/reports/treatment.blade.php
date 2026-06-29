<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Treatment Report') }}</h2>
    </x-slot>

    <form method="GET" class="d-flex gap-2 mb-4">
        <input type="date" name="from" class="form-control w-auto" value="{{ $from->format('Y-m-d') }}">
        <input type="date" name="to" class="form-control w-auto" value="{{ $to->format('Y-m-d') }}">
        <button class="btn btn-primary">Filter</button>
    </form>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card bg-light"><div class="card-body text-center">
                <h3>{{ $report['total'] }}</h3>
                <small>Total</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light"><div class="card-body text-center">
                <h3>{{ $report['by_category']['checkup'] }}</h3>
                <small>Checkups</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light"><div class="card-body text-center">
                <h3>{{ $report['by_category']['treatment'] }}</h3>
                <small>Treatments</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light"><div class="card-body text-center">
                <h3 class="text-danger">{{ $report['by_category']['emergency'] }}</h3>
                <small>Emergencies</small>
            </div></div>
        </div>
    </div>
</x-app-layout>
