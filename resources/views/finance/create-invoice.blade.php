<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Generate Invoice') }}</h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('finance.store-invoice') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">File <span class="text-danger">*</span></label>
                    <select name="patient_file_id" id="patientFileSelect" class="form-select @error('patient_file_id') is-invalid @enderror" required onchange="filterPatients()">
                        <option value="">Select file...</option>
                        @foreach ($patientFiles as $file)
                            <option value="{{ $file->id }}" data-file-id="{{ $file->id }}">
                                {{ $file->file_number }} — {{ $file->name }} ({{ ucfirst($file->type) }})
                            </option>
                        @endforeach
                    </select>
                    @error('patient_file_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Patient <span class="text-danger">*</span></label>
                    <select name="patient_id" id="patientSelect" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">Select a file first...</option>
                        @foreach ($patientFiles as $file)
                            @foreach ($file->patients as $patient)
                                <option value="{{ $patient->id }}" data-file-id="{{ $file->id }}" style="display: none;">
                                    {{ $patient->name }} ({{ $patient->phone ?? '—' }})
                                </option>
                            @endforeach
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

    <script>
        function filterPatients() {
            const fileId = document.getElementById('patientFileSelect').value;
            const patientSelect = document.getElementById('patientSelect');
            const options = patientSelect.querySelectorAll('option[data-file-id]');

            patientSelect.innerHTML = '<option value="">Select patient...</option>';

            if (!fileId) {
                patientSelect.innerHTML = '<option value="">Select a file first...</option>';
                return;
            }

            options.forEach(function(opt) {
                if (opt.getAttribute('data-file-id') === fileId) {
                    const cloned = opt.cloneNode(true);
                    cloned.style.display = '';
                    patientSelect.appendChild(cloned);
                }
            });
        }
    </script>
</x-app-layout>
