<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Generate Invoice') }}</h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('finance.store-invoice') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Patient</label>
                    <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">Select patient...</option>
                        @foreach ($patients as $p)
                            <option value="{{ $p->id }}">{{ $p->file_number }} — {{ $p->name }} ({{ ucfirst($p->account_type) }})</option>
                        @endforeach
                    </select>
                    @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Amount Due</label>
                    <input type="number" step="0.01" name="amount_due" class="form-control @error('amount_due') is-invalid @enderror" required>
                    @error('amount_due') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('finance.invoices') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Generate Invoice</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
