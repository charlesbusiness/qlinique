<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">Partograph — {{ $antenatal->patient->name }}</h2>
            <a href="{{ route('antenatal.show', $antenatal) }}" class="btn btn-outline-primary btn-sm">Back</a>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-header"><strong>Add Entry</strong></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('antenatal.partograph.store', $antenatal) }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cervical Dilation (cm)</label>
                                <input type="number" step="0.5" name="cervical_dilation" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fetal Heart Rate</label>
                                <input type="number" name="fetal_heart_rate" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Maternal Pulse</label>
                                <input type="number" name="maternal_pulse" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">BP Systolic</label>
                                <input type="number" name="blood_pressure_systolic" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">BP Diastolic</label>
                                <input type="number" name="blood_pressure_diastolic" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Temperature (°C)</label>
                                <input type="number" step="0.1" name="temperature" class="form-control">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Labour Progress Notes</label>
                                <textarea name="labour_progress" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Entry</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header"><strong>Partograph History</strong></div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Time</th>
                                <th>Cervix</th>
                                <th>FHR</th>
                                <th>Pulse</th>
                                <th>BP</th>
                                <th>Temp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($antenatal->partographs as $p)
                                <tr>
                                    <td>{{ $p->recorded_at->format('d M H:i') }}</td>
                                    <td>{{ $p->cervical_dilation ?? '—' }}</td>
                                    <td>{{ $p->fetal_heart_rate ?? '—' }}</td>
                                    <td>{{ $p->maternal_pulse ?? '—' }}</td>
                                    <td>{{ $p->blood_pressure_systolic }}/{{ $p->blood_pressure_diastolic }}</td>
                                    <td>{{ $p->temperature ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-3">No entries yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
