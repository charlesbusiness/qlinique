<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Financial Report') }}</h2>
    </x-slot>

    <form method="GET" class="d-flex gap-2 mb-4">
        <input type="date" name="from" class="form-control w-auto" value="{{ $from->format('Y-m-d') }}">
        <input type="date" name="to" class="form-control w-auto" value="{{ $to->format('Y-m-d') }}">
        <button class="btn btn-primary">Filter</button>
    </form>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card bg-light"><div class="card-body text-center">
                <h3>{{ number_format($report['total_invoiced'], 2) }}</h3>
                <small>Total Invoiced</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light"><div class="card-body text-center">
                <h3 class="text-success">{{ number_format($report['total_collected'], 2) }}</h3>
                <small>Collected</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light"><div class="card-body text-center">
                <h3 class="text-danger">{{ number_format($report['outstanding'], 2) }}</h3>
                <small>Outstanding</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light"><div class="card-body text-center">
                <h3>{{ $report['count'] }}</h3>
                <small>Invoices</small>
            </div></div>
        </div>
    </div>
</x-app-layout>
