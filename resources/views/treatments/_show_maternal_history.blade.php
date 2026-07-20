@php
    $patient = $record->patient;
    $chart = $treatment;

    $labelMap = fn(array $options, array $values) => collect($values)->map(fn($v) => $options[$v] ?? ucfirst(str_replace('_', ' ', $v)))->implode(', ');
@endphp

{{-- Row 1: Visit Info + Patient Summary --}}
<div class="row">
    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header"><strong>Visit Info</strong></div>
            <div class="card-body">
                <p class="mb-1"><strong>Category:</strong> <span class="badge bg-warning">{{ \App\Enums\TreatmentCategory::tryFrom($chart->category)?->label() ?? ucfirst($chart->category) }}</span>
                    @if ($chart->sub_category)
                        — {{ (\App\Livewire\TreatmentForm::assessmentSubOptions($chart->category))[$chart->sub_category] ?? $chart->sub_category }}
                    @endif
                </p>
                <p class="mb-1"><strong>Visit Date:</strong> {{ $chart->visit_date?->format('d M Y') ?? '—' }}</p>
                <p class="mb-1"><strong>Status:</strong>
                    @if ($chart->is_completed)
                        <span class="badge bg-success">Completed</span>
                    @else
                        <span class="badge bg-primary">Active</span>
                    @endif
                </p>
                @if ($record->next_visit_date)
                    <p class="mb-1"><strong>Next Visit:</strong> {{ $record->next_visit_date->format('d M Y') }}</p>
                @endif
                @if ($record->attending_physician_name)
                    <p class="mb-0"><strong>Physician:</strong> {{ $record->attending_physician_name }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header"><strong>Patient Information</strong></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4"><strong>Name:</strong> {{ $patient->name ?? '—' }}</div>
                    <div class="col-md-4"><strong>File #:</strong> {{ $patient->file?->file_number ?? '—' }}</div>
                    <div class="col-md-4"><strong>Gender:</strong> {{ $patient->gender ?? '—' }}</div>
                    <div class="col-md-4"><strong>DOB:</strong> {{ $patient->date_of_birth?->format('d M Y') ?? '—' }}</div>
                    <div class="col-md-4"><strong>Phone:</strong> {{ $patient->phone ?? '—' }}</div>
                    <div class="col-md-4"><strong>Blood Group:</strong> {{ $patient->blood_group ?? '—' }}</div>
                    <div class="col-md-4"><strong>Genotype:</strong> {{ $patient->genotype ?? '—' }}</div>
                    <div class="col-md-4"><strong>Marital Status:</strong> {{ $patient->marital_status ?? '—' }}</div>
                    <div class="col-md-4"><strong>Occupation:</strong> {{ $patient->occupation ?? '—' }}</div>
                    <div class="col-md-12"><strong>Address:</strong> {{ $patient->address ?? '—' }}</div>
                </div>

                @if ($patient?->next_of_kin)
                    <hr>
                    <h6 class="text-muted mb-2">Next of Kin</h6>
                    <div class="row g-2">
                        <div class="col-md-3"><strong>Name:</strong> {{ $patient->next_of_kin['name'] ?? '—' }}</div>
                        <div class="col-md-3"><strong>Relationship:</strong> {{ $patient->next_of_kin['relationship'] ?? '—' }}</div>
                        <div class="col-md-3"><strong>Phone:</strong> {{ $patient->next_of_kin['phone'] ?? '—' }}</div>
                        <div class="col-md-3"><strong>Address:</strong> {{ $patient->next_of_kin['address'] ?? '—' }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Row 2: Pregnancy & Obstetric History + Medical & Social History --}}
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><strong>Pregnancy Dating & Current Status</strong></div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-md-4"><strong>LMP:</strong> {{ $record->lmp?->format('d M Y') ?? '—' }}</div>
                    <div class="col-md-4"><strong>Cycle:</strong> {{ $record->cycle_regularity ?? '—' }}</div>
                    <div class="col-md-4"><strong>EDD:</strong> {{ $record->edd?->format('d M Y') ?? '—' }}</div>
                    <div class="col-md-4"><strong>CGA:</strong> {{ $record->cga_weeks ?? '—' }}w {{ $record->cga_days ?? '—' }}d</div>
                </div>

                @if ($record->current_symptoms && count($record->current_symptoms))
                    <p class="mb-1"><strong>Current Symptoms:</strong> {{ $labelMap(\App\Livewire\MaternalHealthForm::$currentSymptomOptions, $record->current_symptoms) }}</p>
                @endif
                @if ($record->medications_exposures)
                    <p class="mb-1"><strong>Medications/Exposures:</strong> {{ $record->medications_exposures }}</p>
                @endif

                <hr>
                <h6 class="mb-2">Obstetric History (GTPAL)</h6>
                <div class="row g-2 mb-3">
                    <div class="col"><strong>G:</strong> {{ $record->gravida ?? '—' }}</div>
                    <div class="col"><strong>T:</strong> {{ $record->term ?? '—' }}</div>
                    <div class="col"><strong>P:</strong> {{ $record->preterm ?? '—' }}</div>
                    <div class="col"><strong>A:</strong> {{ $record->abortion ?? '—' }}</div>
                    <div class="col"><strong>L:</strong> {{ $record->living ?? '—' }}</div>
                </div>

                @php
                    $hasPriorPregnancies = $record->prior_pregnancies && count(array_filter($record->prior_pregnancies, fn($p) => !empty($p['year'] ?? $p['gest_age'] ?? $p['mode_of_delivery'])));
                @endphp
                @if ($hasPriorPregnancies)
                    <h6 class="mb-2">Previous Pregnancies</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr><th>#</th><th>Year</th><th>Gest. Age</th><th>Mode</th><th>Birth Weight</th><th>Complications</th><th>Outcome</th></tr>
                            </thead>
                            <tbody>
                                @foreach ($record->prior_pregnancies as $i => $preg)
                                    @if (!empty($preg['year'] ?? $preg['gest_age'] ?? $preg['mode_of_delivery']))
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $preg['year'] ?? '—' }}</td>
                                            <td>{{ $preg['gest_age'] ?? '—' }}w</td>
                                            <td>{{ $preg['mode_of_delivery'] ?? '—' }}</td>
                                            <td>{{ $preg['birth_weight'] ?? '—' }}</td>
                                            <td>{{ $preg['complications'] ?? '—' }}</td>
                                            <td>{{ $preg['neonatal_outcome'] ?? '—' }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if ($record->prior_csection)
                    <p class="mb-0"><strong>Prior C-Section:</strong> {{ $record->prior_csection }}{{ $record->prior_csection_details ? ' — ' . $record->prior_csection_details : '' }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><strong>Medical & Social History</strong></div>
            <div class="card-body">
                @if ($record->chronic_conditions && count($record->chronic_conditions))
                    <p class="mb-1"><strong>Chronic Conditions:</strong> {{ $labelMap(\App\Livewire\MaternalHealthForm::$chronicConditionOptions, $record->chronic_conditions) }}</p>
                @endif
                @if ($record->chronic_conditions_details)
                    <p class="mb-1"><strong>Chronic Conditions Details:</strong> {{ $record->chronic_conditions_details }}</p>
                @endif
                @if ($record->infectious_disease_history && count($record->infectious_disease_history))
                    <p class="mb-1"><strong>Infectious Disease History:</strong> {{ $labelMap(\App\Livewire\MaternalHealthForm::$infectiousDiseaseOptions, $record->infectious_disease_history) }}</p>
                @endif
                @if ($record->prior_surgeries)
                    <p class="mb-1"><strong>Prior Surgeries:</strong> {{ $record->prior_surgeries }}</p>
                @endif
                @if ($record->allergies)
                    <p class="mb-1"><strong>Allergies:</strong> {{ $record->allergies }}</p>
                @endif
                @if ($record->current_medications)
                    <p class="mb-1"><strong>Current Medications:</strong> {{ $record->current_medications }}</p>
                @endif

                @if ($record->family_genetic_history && count($record->family_genetic_history))
                    <hr>
                    <h6 class="mb-2">Family & Genetic History</h6>
                    @foreach ($record->family_genetic_history as $fKey)
                        <p class="mb-1">
                            <strong>{{ \App\Livewire\MaternalHealthForm::$familyGeneticOptions[$fKey] ?? ucfirst(str_replace('_', ' ', $fKey)) }}:</strong>
                            @if ($fKey === 'genetic_errors' && $record->genetic_errors_selection)
                                {{ \App\Livewire\MaternalHealthForm::$geneticErrorsOptions[$record->genetic_errors_selection] ?? $record->genetic_errors_selection }}
                            @elseif ($fKey === 'heart_defects' && $record->heart_defects_selection)
                                {{ \App\Livewire\MaternalHealthForm::$heartDefectsOptions[$record->heart_defects_selection] ?? $record->heart_defects_selection }}
                            @elseif ($fKey === 'family_history_conditions' && $record->family_history_selection)
                                {{ \App\Livewire\MaternalHealthForm::$familyHistoryOptions[$record->family_history_selection] ?? $record->family_history_selection }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </p>
                    @endforeach
                @endif
                @if ($record->family_history_notes)
                    <p class="mb-1"><strong>Notes:</strong> {{ $record->family_history_notes }}</p>
                @endif

                <hr>
                <h6 class="mb-2">Social & Environmental History</h6>
                <div class="row g-2">
                    <div class="col-md-6"><strong>Tobacco/Vape:</strong> {{ $record->tobacco_vape ?? '—' }}{{ $record->tobacco_packs_per_day ? ' (' . $record->tobacco_packs_per_day . ' packs/day)' : '' }}</div>
                    <div class="col-md-6"><strong>Alcohol:</strong> {{ $record->alcohol ?? '—' }}{{ $record->alcohol_drinks_per_week ? ' (' . $record->alcohol_drinks_per_week . ' drinks/week)' : '' }}</div>
                    <div class="col-md-6"><strong>Recreational Drugs:</strong> {{ $record->recreational_drugs ?? '—' }}{{ $record->recreational_drugs_details ? ' — ' . $record->recreational_drugs_details : '' }}</div>
                    <div class="col-md-6"><strong>Support System:</strong> {{ $record->support_system ?? '—' }}</div>
                    <div class="col-md-4"><strong>Safety:</strong> {{ $record->safety_screening ?? '—' }}</div>
                    <div class="col-md-4"><strong>Financial:</strong> {{ $record->financial_stability ?? '—' }}</div>
                    <div class="col-md-4"><strong>IPV:</strong> {{ $record->intimate_partner_violence ?? '—' }}{{ $record->ipv_details ? ' — ' . $record->ipv_details : '' }}</div>
                    <div class="col-md-6"><strong>Occupation Hazard:</strong> {{ $record->occupation_hazard ?? '—' }}</div>
                    <div class="col-md-6"><strong>Travel History:</strong> {{ $record->travel_history ?? '—' }}</div>
                </div>

                @if ($record->diet_intake)
                    <p class="mb-1 mt-2"><strong>Diet Intake:</strong> {{ \App\Livewire\MaternalHealthForm::$dietIntakeOptions[$record->diet_intake] ?? $record->diet_intake }}</p>
                @endif
                @if ($record->physical_activities)
                    <p class="mb-0"><strong>Physical Activities:</strong> {{ \App\Livewire\MaternalHealthForm::$physicalActivityOptions[$record->physical_activities] ?? $record->physical_activities }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
