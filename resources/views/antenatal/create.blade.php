<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('New Antenatal Record') }}</h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('antenatal.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Patient</label>
                    <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">Select patient...</option>
                        @foreach ($patients as $p)
                            <option value="{{ $p->id }}" @selected(old('patient_id') == $p->id)>{{ $p->file_number }} — {{ $p->name }}</option>
                        @endforeach
                    </select>
                    @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Expected Delivery Date (EDD)</label>
                        <input type="date" name="edd" class="form-control @error('edd') is-invalid @enderror" value="{{ old('edd') }}">
                        @error('edd') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Gestation (weeks)</label>
                        <input type="number" name="gestation_weeks" class="form-control @error('gestation_weeks') is-invalid @enderror" value="{{ old('gestation_weeks') }}">
                        @error('gestation_weeks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Risk Level</label>
                        <select name="risk_level" class="form-select">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Obstetric History</label>
                    <textarea name="obstetric_history" class="form-control" rows="3">{{ old('obstetric_history') }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('antenatal.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Record</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
