<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Invoices') }}</h2>
            @if (Auth::user()->hasPermission('finance.invoices.create'))
            <a href="{{ route('finance.create-invoice') }}" class="btn btn-primary">+ New Invoice</a>
            @endif
        </div>
    </x-slot>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('status') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Invoice #</th>
                        <th>Patient</th>
                        <th>Amount Due</th>
                        <th>Amount Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $invoice)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $invoice->invoice_number }}</span></td>
                            <td>{{ $invoice->patient->name ?? '—' }}</td>
                            <td>{{ number_format($invoice->amount_due, 2) }}</td>
                            <td>{{ number_format($invoice->amount_paid, 2) }}</td>
                            <td><strong>{{ number_format($invoice->balance, 2) }}</strong></td>
                            <td>
                                @php $c = match($invoice->status) { 'paid' => 'success', 'partial' => 'warning', 'cancelled' => 'secondary', default => 'danger' }; @endphp
                                <span class="badge bg-{{ $c }}">{{ ucfirst($invoice->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('finance.show-invoice', $invoice) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No invoices.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
        @if ($invoices->hasPages())
            <div class="card-footer">{{ $invoices->links() }}</div>
        @endif
    </div>
</x-app-layout>
