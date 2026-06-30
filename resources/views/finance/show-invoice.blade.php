<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">Invoice {{ $invoice->invoice_number }}</h2>
            <a href="{{ route('finance.invoices') }}" class="btn btn-outline-primary btn-sm">Back</a>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><strong>Invoice Details</strong></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Patient:</strong> {{ $invoice->patient->name }} ({{ $invoice->patient->file?->file_number ?? '—' }})</p>
                    <p class="mb-1"><strong>Account Type:</strong> <span class="badge bg-info">{{ ucfirst($invoice->account_type ?? '—') }}</span></p>
                    <p class="mb-1"><strong>Amount Due:</strong> {{ number_format($invoice->amount_due, 2) }}</p>
                    <p class="mb-1"><strong>Amount Paid:</strong> {{ number_format($invoice->amount_paid, 2) }}</p>
                    <p class="mb-1"><strong>Balance:</strong> <strong>{{ number_format($invoice->balance, 2) }}</strong></p>
                    <p class="mb-0"><strong>Status:</strong>
                        @php $c = match($invoice->status) { 'paid' => 'success', 'partial' => 'warning', 'cancelled' => 'secondary', default => 'danger' }; @endphp
                        <span class="badge bg-{{ $c }}">{{ ucfirst($invoice->status) }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><strong>Record Payment</strong></div>
                <div class="card-body">
                    @if ($invoice->isFullyPaid())
                        <p class="text-success">This invoice is fully paid.</p>
                    @elseif (Auth::user()->hasPermission('finance.payments.create'))
                        <form method="POST" action="{{ route('finance.store-payment', $invoice) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Amount</label>
                                <input type="number" step="0.01" name="amount" class="form-control" max="{{ $invoice->balance }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                                    <option value="mobile_money">Mobile Money</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Record Payment</button>
                        </form>
                    @else
                        <p class="text-muted">You do not have permission to record payments.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($invoice->payments->isNotEmpty())
        <div class="card">
            <div class="card-header"><strong>Payment History</strong></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr><th>Date</th><th>Amount</th><th>Method</th><th>Receipt</th><th>Notes</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->payments as $payment)
                            <tr>
                                <td>{{ $payment->paid_at->format('d M Y H:i') }}</td>
                                <td>{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ str_replace('_', ' ', ucfirst($payment->payment_method)) }}</td>
                                <td><span class="badge bg-secondary">{{ $payment->receipt_number ?? '—' }}</span></td>
                                <td>{{ $payment->notes ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
