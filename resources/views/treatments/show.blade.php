<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">Treatment Chart — {{ $treatment->patient->name }}</h2>
            <div>
                @if (!$treatment->is_completed && Auth::user()->hasPermission('treatments.edit'))
                <a href="{{ route('treatments.edit', $treatment) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                @endif
                @if (Auth::user()->hasPermission('treatments.compliance'))
                <a href="{{ route('treatments.compliance', $treatment) }}" class="btn btn-outline-info btn-sm">Compliance</a>
                @endif
                @if (!$treatment->is_completed && Auth::user()->hasPermission('treatments.edit'))
                <form action="{{ route('treatments.complete', $treatment) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-success btn-sm">Mark Complete</button>
                </form>
                @endif
                <a href="{{ route('treatments.index') }}" class="btn btn-outline-primary btn-sm">Back</a>
            </div>
        </div>
    </x-slot>

    {{-- Row 1: Visit Info + Vitals | History + Diagnosis --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><strong>Visit Info</strong></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Patient:</strong> {{ $treatment->patient->name }} ({{ $treatment->patient->file?->file_number ?? '—' }})</p>
                    <p class="mb-1"><strong>Category:</strong> <span class="badge bg-warning">{{ \App\Enums\TreatmentCategory::tryFrom($treatment->category)?->label() ?? ucfirst($treatment->category) }}</span>
                        @if ($treatment->category === 'other' && $treatment->other_category)
                            — {{ $treatment->other_category }}
                        @elseif ($treatment->sub_category)
                            — {{ (\App\Livewire\TreatmentForm::subCategoryOptions())[$treatment->sub_category] ?? $treatment->sub_category }}
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
                    <div class="card-header"><strong>Vital Signs</strong></div>
                    <div class="card-body">
                        @foreach ($treatment->vitals as $vital)
                            <div class="row">
                                <div class="col-4"><strong>Temp:</strong> {{ $vital->temperature ?? '—' }} {{ $vital->temperature_unit === 'fahrenheit' ? '°F' : '°C' }}</div>
                                <div class="col-4"><strong>BP:</strong> {{ $vital->blood_pressure_systolic ?? '—' }}/{{ $vital->blood_pressure_diastolic ?? '—' }} mmHg</div>
                                <div class="col-4"><strong>Pulse:</strong> {{ $vital->pulse_rate ?? '—' }} bpm</div>
                                <div class="col-4"><strong>RR:</strong> {{ $vital->respiratory_rate ?? '—' }} bpm</div>
                                <div class="col-4"><strong>SpO2:</strong> {{ $vital->oxygen_saturation ?? '—' }}%</div>
                                <div class="col-4"><strong>Weight:</strong> {{ $vital->weight ?? '—' }} kg</div>
                                <div class="col-4"><strong>Height:</strong> {{ $vital->height ?? '—' }} cm</div>
                                <div class="col-4"><strong>BMI:</strong> {{ $vital->bmi ?? '—' }}</div>
                            </div>
                            @if ($vital->comment)
                                <p class="mb-0 mt-2"><strong>Comment:</strong> {{ $vital->comment }}</p>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><strong>Case History</strong></div>
                <div class="card-body">
                    @if ($treatment->presenting_complaint)
                        <p class="mb-1"><strong>Presenting Complaint:</strong> {{ $treatment->presenting_complaint }}</p>
                    @endif
                    @if ($treatment->symptoms)
                        <p class="mb-1"><strong>Symptoms:</strong> {{ $treatment->symptoms }}</p>
                    @endif
                    <p class="mb-1"><strong>Finding on History:</strong> {{ $treatment->finding_on_history ?? '—' }}</p>
                    <p class="mb-1"><strong>Previous Treatment History:</strong> {{ $treatment->previous_treatment_history ?? '—' }}</p>
                    <p class="mb-1"><strong>Recommended Drugs / Routine:</strong> {{ $treatment->recommended_drugs ?? '—' }}</p>
                    <p class="mb-0"><strong>Allergies / Adverse Effects:</strong> {{ $treatment->allergies ?? '—' }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><strong>Diagnosis</strong></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Primary:</strong> {{ $treatment->primary_diagnosis ?? '—' }}</p>
                    @if ($treatment->secondary_diagnosis)
                        <p class="mb-1"><strong>Secondary:</strong> {{ $treatment->secondary_diagnosis }}</p>
                    @endif
                    @if ($treatment->diagnosis_notes)
                        <p class="mb-1"><strong>Notes:</strong> {{ $treatment->diagnosis_notes }}</p>
                    @endif
                    @if ($treatment->recommendations)
                        <p class="mb-0"><strong>Recommendations:</strong> {{ $treatment->recommendations }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Physical Examination --}}
    @if ($treatment->physicalExaminations->isNotEmpty())
        <div class="card mb-4">
            <div class="card-header"><strong>Physical Examination</strong></div>
            <div class="card-body">
                @php $examMap = $treatment->physicalExaminations->keyBy('section'); @endphp

                @if ($examMap->has('anthropometry'))
                    <div class="mb-3">
                        <h6 class="fw-bold">Anthropometry</h6>
                        <p class="mb-0">Weight: {{ $treatment->vitals->first()?->weight ?? '—' }} kg |
                           Height: {{ $treatment->vitals->first()?->height ?? '—' }} cm |
                           BMI: {{ $treatment->vitals->first()?->bmi ?? '—' }}</p>
                        @if ($examMap->get('anthropometry')->comment)
                            <p class="mb-0 text-muted small">{{ $examMap->get('anthropometry')->comment }}</p>
                        @endif
                    </div>
                @endif

                @if ($examMap->has('heart_lungs'))
                    <div class="mb-3">
                        <h6 class="fw-bold">Heart & Lungs</h6>
                        @php $hl = $examMap->get('heart_lungs'); @endphp
                        @if ($hl->findings)
                            <p class="mb-0">{{ implode(', ', array_map('ucfirst', str_replace('_', ' ', $hl->findings))) }}</p>
                        @else
                            <p class="mb-0 text-muted">—</p>
                        @endif
                        @if ($hl->comment)
                            <p class="mb-0 text-muted small">{{ $hl->comment }}</p>
                        @endif
                    </div>
                @endif

                @if ($examMap->has('eyes_ears_nose_throat'))
                    <div class="mb-3">
                        <h6 class="fw-bold">Eyes / Ears / Nose / Throat</h6>
                        @php $eent = $examMap->get('eyes_ears_nose_throat'); $parts = ['eyes' => 'Eyes', 'ears' => 'Ears', 'nose' => 'Nose', 'throat' => 'Throat']; @endphp
                        @forelse ($parts as $key => $label)
                            @if (!empty($eent->findings[$key]))
                                <p class="mb-0"><strong>{{ $label }}:</strong> {{ implode(', ', array_map('ucfirst', str_replace('_', ' ', $eent->findings[$key]))) }}</p>
                            @endif
                        @empty
                            <p class="mb-0 text-muted">—</p>
                        @endforelse
                        @if ($eent->comment)
                            <p class="mb-0 text-muted small">{{ $eent->comment }}</p>
                        @endif
                    </div>
                @endif

                @if ($examMap->has('abdominal'))
                    <div class="mb-3">
                        <h6 class="fw-bold">Abdominal Examination</h6>
                        @php $abd = $examMap->get('abdominal'); @endphp
                        @if ($abd->findings)
                            <p class="mb-0">{{ implode(', ', array_map('ucfirst', str_replace('_', ' ', $abd->findings))) }}</p>
                        @else
                            <p class="mb-0 text-muted">—</p>
                        @endif
                        @if ($abd->comment)
                            <p class="mb-0 text-muted small">{{ $abd->comment }}</p>
                        @endif
                    </div>
                @endif

                @if ($examMap->has('reflex'))
                    <div class="mb-3">
                        <h6 class="fw-bold">Reflex / Nerves Function</h6>
                        @php $ref = $examMap->get('reflex'); @endphp
                        @if ($ref->findings)
                            <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $ref->findings[0])) }}</p>
                        @else
                            <p class="mb-0 text-muted">—</p>
                        @endif
                        @if ($ref->comment)
                            <p class="mb-0 text-muted small">{{ $ref->comment }}</p>
                        @endif
                    </div>
                @endif

                @if ($examMap->has('hair'))
                    <div class="mb-3">
                        <h6 class="fw-bold">Hair Condition</h6>
                        @php $hair = $examMap->get('hair'); @endphp
                        @if ($hair->findings)
                            <p class="mb-0">{{ implode(', ', array_map('ucfirst', str_replace('_', ' ', $hair->findings))) }}</p>
                        @else
                            <p class="mb-0 text-muted">—</p>
                        @endif
                        @if ($hair->comment)
                            <p class="mb-0 text-muted small">{{ $hair->comment }}</p>
                        @endif
                    </div>
                @endif

                @if ($examMap->has('skin'))
                    <div class="mb-0">
                        <h6 class="fw-bold">Skin Scanning</h6>
                        @php $skin = $examMap->get('skin'); @endphp
                        @if ($skin->findings)
                            <p class="mb-0">{{ implode(', ', array_map('ucfirst', str_replace('_', ' ', $skin->findings))) }}</p>
                        @else
                            <p class="mb-0 text-muted">—</p>
                        @endif
                        @if ($skin->comment)
                            <p class="mb-0 text-muted small">{{ $skin->comment }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Row 2: RME | Lab Tests --}}
    <div class="row">
        @if ($treatment->rmeResults->isNotEmpty())
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header"><strong>Rapid Medical Examination (RME)</strong></div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr><th>Test</th><th>Result</th><th>Amount (₦)</th></tr>
                            </thead>
                            <tbody>
                                @foreach ($treatment->rmeResults as $rme)
                                    <tr>
                                        <td>{{ $rme->test_name }}</td>
                                        <td>{{ $rme->result ?? '—' }}</td>
                                        <td>{{ number_format($rme->amount ?? 0, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($treatment->rme_comment)
                        <div class="card-footer text-muted small">{{ $treatment->rme_comment }}</div>
                    @endif
                </div>
            </div>
        @endif

        @if ($treatment->labTests->isNotEmpty())
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header"><strong>Lab Tests</strong></div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr><th>Test</th><th>Sample</th><th>Findings</th><th>Amount (₦)</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @foreach ($treatment->labTests as $lab)
                                    <tr>
                                        <td>{{ $lab->test_type }}</td>
                                        <td>{{ $lab->sample_type ?? '—' }}</td>
                                        <td>{{ Str::limit($lab->findings, 30) ?? '—' }}</td>
                                        <td>{{ number_format($lab->amount ?? 0, 2) }}</td>
                                        <td>
                                            @if($lab->is_completed)<span class="badge bg-success">Completed</span>
                                            @else<span class="badge bg-warning">Pending</span>@endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Treatment Plan Items --}}
    @if ($treatment->treatmentPlanItems->isNotEmpty())
        <div class="card mb-4">
            <div class="card-header"><strong>Treatment Plan</strong></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Drug</th>
                            <th>Route</th>
                            <th>Strength</th>
                            <th>Dosage</th>
                            <th>Regime</th>
                            <th>Duration</th>
                            <th>Amount (₦)</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($treatment->treatmentPlanItems as $item)
                            <tr>
                                <td>{{ $item->drug_name }}</td>
                                <td>{{ $item->route_form ?? $item->route_category }}</td>
                                <td>{{ $item->strength ? strtoupper($item->strength) : '—' }}</td>
                                <td>{{ $item->dosage ?? '—' }}</td>
                                <td>{{ strtoupper($item->regime) }}</td>
                                <td>{{ $item->length_value }}/{{ $item->length_unit }}</td>
                                <td>{{ number_format($item->amount ?? 0, 2) }}</td>
                                <td>
                                    @if($item->is_take_home)<span class="badge bg-info">Take Home</span>
                                    @else<span class="badge bg-secondary">In Facility</span>@endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Row 3: Medical Bill | Consent --}}
    <div class="row">
        @if ($treatment->medical_bill)
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header"><strong>Medical Bill</strong></div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tbody>
                                @foreach (['registration' => 'Registration', 'consultation' => 'Consultation', 'rapid_medical_examination' => 'RME', 'laboratory_test' => 'Lab Test', 'admission' => 'Admission', 'medical_service' => 'Medical Service', 'logistics' => 'Logistics', 'maintenance' => 'Maintenance', 'surgical_procedure' => 'Surgical Procedure'] as $key => $label)
                                    @if (($treatment->medical_bill[$key] ?? 0) > 0)
                                        <tr>
                                            <td>{{ $label }}</td>
                                            <td class="text-end">₦{{ number_format($treatment->medical_bill[$key], 2) }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr class="table-active fw-bold">
                                    <td>Current Total</td>
                                    <td class="text-end">₦{{ number_format($treatment->medical_bill['total'] ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Paid</td>
                                    <td class="text-end">₦{{ number_format($treatment->medical_bill['paid'] ?? 0, 2) }}</td>
                                </tr>
                                <tr class="fw-bold {{ ($treatment->medical_bill['outstanding'] ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                                    <td>Pending Balance</td>
                                    <td class="text-end">₦{{ number_format($treatment->medical_bill['outstanding'] ?? 0, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if ($treatment->consent)
            <div class="col-md-6">
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
            </div>
        @endif
    </div>

    <div class="card">
        <div class="card-body">
            @livewire('document-upload', ['documentable' => $treatment], key('treatment-docs-' . $treatment->id))
        </div>
    </div>
</x-app-layout>
