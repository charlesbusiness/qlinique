@php
    $labelMap = fn(array $options, array $values) => collect($values)->map(fn($v) => $options[$v] ?? ucfirst(str_replace('_', ' ', $v)))->implode(', ');
@endphp

{{-- Vital Signs & RME --}}
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><strong>Vital Signs</strong></div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-4"><strong>Temp:</strong> {{ $record->temperature ?? '—' }} {{ $record->temperature_unit === 'fahrenheit' ? '°F' : '°C' }}</div>
                    <div class="col-4"><strong>BP:</strong> {{ $record->bp_systolic ?? '—' }}/{{ $record->bp_diastolic ?? '—' }} mmHg</div>
                    <div class="col-4"><strong>Pulse:</strong> {{ $record->pulse_bpm ?? '—' }} bpm</div>
                    <div class="col-4"><strong>RR:</strong> {{ $record->respiration_bpm ?? '—' }} bpm</div>
                    <div class="col-4"><strong>Weight:</strong> {{ $record->weight ?? '—' }} kg</div>
                    <div class="col-4"><strong>Height:</strong> {{ $record->height ?? '—' }} cm</div>
                    <div class="col-4"><strong>BMI:</strong> {{ $record->bmi ?? '—' }}</div>
                </div>
                @if ($record->vitals_comment)
                    <p class="mb-0 mt-2"><strong>Comment:</strong> {{ $record->vitals_comment }}</p>
                @endif
                @if ($record->anthropometric_comment)
                    <p class="mb-0"><strong>Anthropometry Comment:</strong> {{ $record->anthropometric_comment }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><strong>Rapid Medical Examination (RME)</strong></div>
            <div class="card-body">
                <div class="row g-2">
                    @if ($record->rme_fbs)<div class="col-4"><strong>FBS:</strong> {{ $record->rme_fbs }}</div>@endif
                    @if ($record->rme_rbs)<div class="col-4"><strong>RBS:</strong> {{ $record->rme_rbs }}</div>@endif
                    @if ($record->rme_pcv)<div class="col-4"><strong>PCV:</strong> {{ $record->rme_pcv }}%</div>@endif
                    @if ($record->rme_rdta)<div class="col-4"><strong>RDT-A:</strong> {{ $record->rme_rdta }}</div>@endif
                    @if ($record->rme_glucose)<div class="col-4"><strong>Glucose:</strong> {{ $record->rme_glucose }}</div>@endif
                    @if ($record->rme_protein)<div class="col-4"><strong>Protein:</strong> {{ $record->rme_protein }}</div>@endif
                    @if ($record->rme_leukocytes)<div class="col-6"><strong>Leukocytes:</strong> {{ $record->rme_leukocytes }}</div>@endif
                    @if ($record->rme_other_specify)<div class="col-6"><strong>{{ $record->rme_other_specify }}:</strong> {{ $record->rme_other_result ?? '—' }}</div>@endif
                </div>
                @if ($record->rme_comment)
                    <p class="mb-0 mt-2"><strong>Comment:</strong> {{ $record->rme_comment }}</p>
                @endif
                @if (!$record->rme_fbs && !$record->rme_rbs && !$record->rme_pcv && !$record->rme_comment)
                    <p class="text-muted mb-0">—</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Physical & Obstetric Exam --}}
<div class="card mb-4">
    <div class="card-header"><strong>Physical & Obstetric Examination</strong></div>
    <div class="card-body">
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <h6 class="fw-bold">1. Cardiovascular & Respiratory</h6>
                @if ($record->cardio_resp && count($record->cardio_resp))
                    <p class="mb-0">{{ $labelMap(\App\Livewire\MaternalHealthForm::$cardioRespOptions, $record->cardio_resp) }}</p>
                @else
                    <p class="text-muted mb-0">—</p>
                @endif
                @if ($record->cardio_resp_comment)<p class="mb-0 text-muted small">{{ $record->cardio_resp_comment }}</p>@endif
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold">2. Thyroid</h6>
                @if ($record->thyroid && count($record->thyroid))
                    <p class="mb-0">{{ $labelMap(\App\Livewire\MaternalHealthForm::$thyroidOptions, $record->thyroid) }}</p>
                @else
                    <p class="text-muted mb-0">—</p>
                @endif
                @if ($record->thyroid_comment)<p class="mb-0 text-muted small">{{ $record->thyroid_comment }}</p>@endif
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold">3. Breast</h6>
                @if ($record->breast && count($record->breast))
                    <p class="mb-0">{{ $labelMap(\App\Livewire\MaternalHealthForm::$breastOptions, $record->breast) }}</p>
                @else
                    <p class="text-muted mb-0">—</p>
                @endif
                @if ($record->breast_comment)<p class="mb-0 text-muted small">{{ $record->breast_comment }}</p>@endif
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold">4. Extremities</h6>
                @if ($record->extremities && count($record->extremities))
                    <p class="mb-0">{{ $labelMap(\App\Livewire\MaternalHealthForm::$extremitiesOptions, $record->extremities) }}</p>
                @else
                    <p class="text-muted mb-0">—</p>
                @endif
                @if ($record->extremities_comment)<p class="mb-0 text-muted small">{{ $record->extremities_comment }}</p>@endif
            </div>
        </div>

        <hr>

        <h6 class="fw-bold mb-3">Obstetric / Abdominal Examination</h6>
        <div class="row g-3 mb-3">
            <div class="col-md-3"><strong>Fundal Height:</strong> {{ $record->fundal_height_cm ?? '—' }} cm</div>
            <div class="col-md-3"><strong>FHR:</strong> {{ $record->fetal_heart_rate_bpm ?? '—' }} bpm</div>
        </div>

        <h6 class="fw-bold mb-2">Leopold's Maneuvers</h6>
        <div class="row g-3 mb-3">
            <div class="col-md-3"><strong>Fetal Lie:</strong> {{ $record->fetal_lie ?? '—' }}</div>
            <div class="col-md-3"><strong>Presentation:</strong> {{ $record->fetal_presentation ?? '—' }}</div>
            <div class="col-md-3"><strong>Position:</strong> {{ $record->fetal_position ?? '—' }}</div>
            <div class="col-md-3"><strong>Engagement:</strong> {{ $record->fetal_engagement ?? '—' }}</div>
        </div>

        @if ($record->pelvic_vaginal_findings)
            <h6 class="fw-bold mb-2">Pelvic & Vaginal Examination</h6>
            <p class="mb-0">{{ $record->pelvic_vaginal_findings }}</p>
        @endif
    </div>
</div>

{{-- Investigation & Treatment --}}
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><strong>Lab Tests</strong></div>
            <div class="card-body p-0">
                @php
                    $hasLabTests = $record->lab_tests && count(array_filter($record->lab_tests, fn($t) => !empty($t['name'] ?? $t['test_type'] ?? '')));
                @endphp
                @if ($hasLabTests)
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr><th>Test</th><th>Specimen</th><th>Amount (₦)</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($record->lab_tests as $lab)
                                @if (!empty($lab['name'] ?? $lab['test_type'] ?? ''))
                                    <tr>
                                        <td>{{ $lab['name'] ?? $lab['test_type'] ?? '—' }}</td>
                                        <td>{{ $lab['specimen'] ?? $lab['sample_type'] ?? '—' }}</td>
                                        <td>{{ number_format($lab['amount'] ?? 0, 2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted mb-0 p-3">No lab tests recorded.</p>
                @endif
            </div>
            @if ($record->lab_investigation_comment)
                <div class="card-footer text-muted small">{{ $record->lab_investigation_comment }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><strong>Clinical Diagnosis</strong></div>
            <div class="card-body">
                <p class="mb-0">{{ $record->clinical_judgement_diagnosis ?? '—' }}</p>
            </div>
        </div>
    </div>
</div>

@php
    $hasMedications = $record->medications && count(array_filter($record->medications, fn($m) => !empty($m['drug_name'])));
@endphp
@if ($hasMedications)
    <div class="card mb-4">
        <div class="card-header"><strong>Medications / Treatment Plan</strong></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Route</th>
                            <th>Form</th>
                            <th>Drug</th>
                            <th>Strength</th>
                            <th>Dosage</th>
                            <th>Regime</th>
                            <th>Duration</th>
                            <th>Amount (₦)</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($record->medications as $med)
                            @if (!empty($med['drug_name']))
                                <tr>
                                    <td>{{ (\App\Livewire\MaternalHealthForm::$routeCategories)[$med['route_category'] ?? ''] ?? $med['route_category'] ?? '—' }}</td>
                                    <td>{{ ucfirst($med['route_form'] ?? '—') }}</td>
                                    <td>{{ $med['drug_name'] }}</td>
                                    <td>{{ strtoupper($med['strength'] ?? '—') }}</td>
                                    <td>{{ $med['dosage'] ?? '—' }}</td>
                                    <td>{{ strtoupper($med['regime'] ?? '—') }}</td>
                                    <td>{{ $med['length_value'] ?? '—' }}/{{ $med['length_unit'] ?? '—' }}</td>
                                    <td>{{ number_format($med['amount'] ?? 0, 2) }}</td>
                                    <td>
                                        @if(($med['is_take_home'] ?? 0) == 1)<span class="badge bg-info">Take Home</span>
                                        @else<span class="badge bg-secondary">In Facility</span>@endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

{{-- Billing & Consent --}}
<div class="row">
    <div class="col-md-6">
                @if ($record->medical_bill)
            <div class="card mb-4">
                <div class="card-header"><strong>Medical Bill</strong></div>
                <div class="card-body">
                    @php
                        $labTotal = collect($record->lab_tests ?? [])->sum('amount');
                        $medTotal = collect($record->medications ?? [])->sum('amount');
                        $manualItems = collect($record->medical_bill ?? [])->only(['registration', 'consultation', 'rapid_medical_examination', 'admission', 'logistics', 'maintenance', 'surgical_procedure']);
                        $billTotal = $labTotal + $medTotal + $manualItems->sum();
                        $billPaid = $record->bill_paid ?? 0;
                        $billOutstanding = $billTotal - $billPaid;
                    @endphp
                    <table class="table table-sm mb-0">
                        <tbody>
                            @if ($labTotal > 0)
                                <tr>
                                    <td>Lab Test</td>
                                    <td class="text-end">₦{{ number_format($labTotal, 2) }}</td>
                                </tr>
                            @endif
                            @if ($medTotal > 0)
                                <tr>
                                    <td>Medical Service</td>
                                    <td class="text-end">₦{{ number_format($medTotal, 2) }}</td>
                                </tr>
                            @endif
                            @foreach (['registration' => 'Registration', 'consultation' => 'Consultation', 'rapid_medical_examination' => 'RME', 'admission' => 'Admission', 'logistics' => 'Logistics', 'maintenance' => 'Maintenance', 'surgical_procedure' => 'Surgical Procedure'] as $key => $label)
                                @if ($manualItems->get($key, 0) > 0)
                                    <tr>
                                        <td>{{ $label }}</td>
                                        <td class="text-end">₦{{ number_format($manualItems->get($key), 2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr class="table-active fw-bold">
                                <td>Current Total</td>
                                <td class="text-end">₦{{ number_format($billTotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Paid</td>
                                <td class="text-end">₦{{ number_format($billPaid, 2) }}</td>
                            </tr>
                            <tr class="fw-bold {{ $billOutstanding > 0 ? 'text-danger' : 'text-success' }}">
                                <td>Outstanding Balance</td>
                                <td class="text-end">₦{{ number_format($billOutstanding, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><strong>Consent & Referral</strong></div>
            <div class="card-body">
                <p class="mb-1"><strong>Informed Consent:</strong>
                    @if ($record->consent_enabled)<span class="badge bg-success">Enabled</span>
                    @else<span class="badge bg-secondary">Not Enabled</span>@endif
                </p>
                <p class="mb-1"><strong>Referral Letter:</strong>
                    @if ($record->referral_letter)<span class="badge bg-info">Included</span>
                    @else<span class="badge bg-secondary">Not Included</span>@endif
                </p>
                @if ($record->attending_physician_name)
                    <hr>
                    <p class="mb-1"><strong>Attending Physician:</strong> {{ $record->attending_physician_name }}</p>
                @endif
                @if ($record->attending_physician_signature)
                    <p class="mb-1"><strong>Physician Signature:</strong>
                        <span style="font-family: 'Dancing Script', 'Pacifico', cursive; font-size: 1.2rem;">{{ $record->attending_physician_signature }}</span>
                    </p>
                @endif
                @if ($record->attending_physician_date)
                    <p class="mb-0"><strong>Date Signed:</strong> {{ $record->attending_physician_date->format('d M Y') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @livewire('document-upload', ['documentable' => $treatment], key('maternal-docs-' . $treatment->id))
    </div>
</div>
