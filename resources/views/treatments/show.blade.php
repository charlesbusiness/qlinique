<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">Treatment Chart — {{ $treatment->patient->name }}</h2>
            <div>
                @if (Auth::user()->hasPermission('treatments.compliance'))
                <a href="{{ route('treatments.compliance', $treatment) }}" class="btn btn-outline-info btn-sm">Compliance</a>
                @endif
                <a href="{{ route('treatments.index') }}" class="btn btn-outline-primary btn-sm">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><strong>Visit Info</strong></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Patient:</strong> {{ $treatment->patient->name }} ({{ $treatment->patient->file_number }})</p>
                    <p class="mb-1"><strong>Category:</strong> <span class="badge bg-warning">{{ \App\Enums\TreatmentCategory::tryFrom($treatment->category)?->label() ?? ucfirst($treatment->category) }}</span>
                        @if ($treatment->category === 'other' && $treatment->other_category)
                            — {{ $treatment->other_category }}
                        @elseif ($treatment->sub_category)
                            — {{ (\App\Livewire\TreatmentForm::subCategoryOptions($treatment->category))[$treatment->sub_category] ?? $treatment->sub_category }}
                        @endif
                    </p>
                    <p class="mb-1"><strong>Visit Date:</strong> {{ $treatment->visit_date->format('d M Y') }}</p>
                    <p class="mb-1"><strong>Schedule:</strong> {{ $treatment->treatment_schedule ?? '—' }}</p>
                    <p class="mb-0"><strong>Status:</strong>
                        @if ($treatment->is_completed)
                            <span class="badge bg-success">Completed</span>
                        @else
                            <span class="badge bg-primary">Active</span>
                        @endif
                    </p>
                </div>
            </div>

            @if ($treatment->vitals->isNotEmpty())
                <div class="card mb-4">
                    <div class="card-header"><strong>Vitals</strong></div>
                    <div class="card-body">
                        @foreach ($treatment->vitals as $vital)
                            <div class="row">
                                <div class="col-4"><strong>Temp:</strong> {{ $vital->temperature ?? '—' }} {{ $vital->temperature_unit === 'fahrenheit' ? '°F' : '°C' }}</div>
                                <div class="col-4"><strong>BP:</strong> {{ $vital->blood_pressure_systolic }}/{{ $vital->blood_pressure_diastolic }} mmHg</div>
                                <div class="col-4"><strong>Pulse:</strong> {{ $vital->pulse_rate ?? '—' }} bpm</div>
                                <div class="col-4"><strong>RR:</strong> {{ $vital->respiratory_rate ?? '—' }} bpm</div>
                                <div class="col-4"><strong>SpO2:</strong> {{ $vital->oxygen_saturation ?? '—' }}%</div>
                                <div class="col-4"><strong>Weight:</strong> {{ $vital->weight ?? '—' }} kg</div>
                                <div class="col-4"><strong>Height:</strong> {{ $vital->height ?? '—' }} cm</div>
                                <div class="col-4"><strong>BMI:</strong> {{ $vital->bmi ?? '—' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><strong>Diagnosis</strong></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Primary:</strong> {{ $treatment->primary_diagnosis ?? '—' }}</p>
                    <p class="mb-1"><strong>Secondary:</strong> {{ $treatment->secondary_diagnosis ?? '—' }}</p>
                    @if ($treatment->recommendations)
                        <p class="mb-1"><strong>Recommendations:</strong> {{ $treatment->recommendations }}</p>
                    @endif
                    <p class="mb-0"><strong>Notes:</strong> {{ $treatment->diagnosis_notes ?? '—' }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><strong>History</strong></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Complaint:</strong> {{ $treatment->presenting_complaint ?? '—' }}</p>
                    <p class="mb-1"><strong>Symptoms:</strong> {{ $treatment->symptoms ?? '—' }}</p>
                    <p class="mb-0"><strong>Previous:</strong> {{ $treatment->previous_treatment_history ?? '—' }}</p>
                </div>
            </div>

            @if ($treatment->medications->isNotEmpty())
                <div class="card mb-4">
                    <div class="card-header"><strong>Medications</strong></div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr><th>Drug</th><th>Qty</th><th>Dosage</th><th>Duration</th><th>Type</th></tr>
                            </thead>
                            <tbody>
                                @foreach ($treatment->medications as $med)
                                    <tr>
                                        <td>{{ $med->drug_name }}</td>
                                        <td>{{ $med->quantity }}</td>
                                        <td>{{ $med->dosage ?? '—' }}</td>
                                        <td>{{ $med->duration ?? '—' }}</td>
                                        <td>@if($med->is_take_home)<span class="badge bg-info">Take Home</span>@else<span class="badge bg-secondary">In Facility</span>@endif</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if ($treatment->labTests->isNotEmpty())
                <div class="card mb-4">
                    <div class="card-header"><strong>Lab Tests</strong></div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr><th>Test</th><th>Findings</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @foreach ($treatment->labTests as $lab)
                                    <tr>
                                        <td>{{ $lab->test_type }}</td>
                                        <td>{{ Str::limit($lab->findings, 30) ?? '—' }}</td>
                                        <td>@if($lab->is_completed)<span class="badge bg-success">Completed</span>@else<span class="badge bg-warning">Pending</span>@endif</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><strong>Treatment Plan</strong></div>
        <div class="card-body">
            <p>{{ $treatment->treatment_plan ?? 'No treatment plan recorded.' }}</p>
            @if ($treatment->take_home_medication)
                <p class="mb-0"><strong>Take-Home Instructions:</strong> {{ $treatment->take_home_medication }}</p>
            @endif
        </div>
    </div>

    @if ($treatment->consent)
    <div class="card mb-4">
        <div class="card-header"><strong>Informed Consent</strong></div>
        <div class="card-body">
            <p class="mb-1"><strong>Procedure:</strong> {{ $treatment->consent['procedure_description'] ?? '—' }}</p>
            <p class="mb-1"><strong>Attending Physician:</strong> {{ $treatment->consent['attending_physician'] ?? '—' }}</p>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <h6>Patient / Representative</h6>
                    @if (($treatment->consent['patient_signature_type'] ?? '') === 'typed' && ($treatment->consent['patient_signature'] ?? ''))
                        <p class="mb-1" style="font-family: 'Dancing Script', 'Pacifico', cursive; font-size: 1.3rem;">{{ $treatment->consent['patient_signature'] }}</p>
                    @elseif (($treatment->consent['patient_signature_type'] ?? '') === 'uploaded' && ($treatment->consent['patient_signature_upload'] ?? ''))
                        <img src="{{ asset('storage/' . $treatment->consent['patient_signature_upload']) }}" class="border rounded" style="max-height: 50px;">
                    @else
                        <p class="text-muted mb-1">—</p>
                    @endif
                </div>
                <div class="col-md-4">
                    <h6>Witness</h6>
                    <p class="mb-1"><strong>{{ $treatment->consent['witness_name'] ?? '—' }}</strong></p>
                    @if (($treatment->consent['witness_signature_type'] ?? '') === 'typed' && ($treatment->consent['witness_signature'] ?? ''))
                        <p class="mb-1" style="font-family: 'Dancing Script', 'Pacifico', cursive; font-size: 1.3rem;">{{ $treatment->consent['witness_signature'] }}</p>
                    @elseif (($treatment->consent['witness_signature_type'] ?? '') === 'uploaded' && ($treatment->consent['witness_signature_upload'] ?? ''))
                        <img src="{{ asset('storage/' . $treatment->consent['witness_signature_upload']) }}" class="border rounded" style="max-height: 50px;">
                    @else
                        <p class="text-muted mb-1">—</p>
                    @endif
                </div>
                <div class="col-md-4">
                    <h6>Physician</h6>
                    @if (($treatment->consent['physician_signature_type'] ?? '') === 'typed' && ($treatment->consent['physician_signature'] ?? ''))
                        <p class="mb-1" style="font-family: 'Dancing Script', 'Pacifico', cursive; font-size: 1.3rem;">{{ $treatment->consent['physician_signature'] }}</p>
                    @elseif (($treatment->consent['physician_signature_type'] ?? '') === 'uploaded' && ($treatment->consent['physician_signature_upload'] ?? ''))
                        <img src="{{ asset('storage/' . $treatment->consent['physician_signature_upload']) }}" class="border rounded" style="max-height: 50px;">
                    @else
                        <p class="text-muted mb-1">—</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            @livewire('document-upload', ['documentable' => $treatment], key('treatment-docs-' . $treatment->id))
        </div>
    </div>
</x-app-layout>
