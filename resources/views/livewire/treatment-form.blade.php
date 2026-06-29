<div>
    <div class="mb-4">
        <div class="d-flex gap-2">
            @foreach ([1 => 'Patient', 2 => 'Vitals', 3 => 'History & Diagnosis', 4 => 'Labs & Treatment', 5 => 'Summary'] as $num => $label)
                <span class="badge {{ $step >= $num ? 'bg-primary' : 'bg-secondary' }} fs-6 px-3 py-2">{{ $num }}. {{ $label }}</span>
            @endforeach
        </div>
    </div>

    <form wire:submit="save">
        {{-- Step 1: Patient & Category --}}
        @if ($step === 1)
            <div class="mb-3">
                <label class="form-label">Patient</label>
                <select class="form-select @error('patientId') is-invalid @enderror" wire:model="patientId">
                    <option value="">Select patient...</option>
                    @foreach ($patients as $p)
                        <option value="{{ $p->id }}">{{ $p->file_number }} — {{ $p->name }}</option>
                    @endforeach
                </select>
                @error('patientId') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Category</label>
                    <select class="form-select @error('category') is-invalid @enderror" wire:model.live="category">
                        @foreach (\App\Enums\TreatmentCategory::options() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    @if ($category === 'other')
                        <div class="mt-2">
                            <label class="form-label">Specify Category</label>
                            <input type="text" class="form-control @error('other_category') is-invalid @enderror" wire:model="other_category" placeholder="Please specify...">
                            @error('other_category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    @endif
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Visit Date</label>
                    <input type="date" class="form-control @error('visit_date') is-invalid @enderror" wire:model="visit_date">
                    @error('visit_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        @endif

        {{-- Step 2: Vitals --}}
        @if ($step === 2)
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Temperature</label>
                    <div class="input-group">
                        <input type="number" step="0.1" class="form-control" wire:model="vitals.temperature">
                        <select class="form-select" style="max-width: 80px;" wire:model="vitals.temperature_unit">
                            <option value="celsius">°C</option>
                            <option value="fahrenheit">°F</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Blood Pressure (Systolic) (mmHg)</label>
                    <input type="number" class="form-control" wire:model="vitals.blood_pressure_systolic">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Blood Pressure (Diastolic) (mmHg)</label>
                    <input type="number" class="form-control" wire:model="vitals.blood_pressure_diastolic">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Pulse Rate (bpm)</label>
                    <input type="number" class="form-control" wire:model="vitals.pulse_rate">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Respiratory Rate (bpm)</label>
                    <input type="number" class="form-control" wire:model="vitals.respiratory_rate">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Oxygen Saturation (%)</label>
                    <input type="number" class="form-control" wire:model="vitals.oxygen_saturation">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Weight (kg)</label>
                    <input type="number" step="0.1" class="form-control" wire:model="vitals.weight">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Height (cm)</label>
                    <input type="number" step="0.1" class="form-control" wire:model="vitals.height">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">BMI</label>
                    <input type="number" step="0.1" class="form-control" wire:model="vitals.bmi" readonly>
                </div>
            </div>
        @endif

        {{-- Step 3: History & Diagnosis --}}
        @if ($step === 3)
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Finding on history</label>
                    <textarea class="form-control" wire:model="presenting_complaint" rows="2"></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Finding on Examination (Sign and Symptoms)</label>
                    <textarea class="form-control" wire:model="symptoms" rows="2"></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Previous Treatment History</label>
                    <textarea class="form-control" wire:model="previous_treatment_history" rows="2"></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Clinical Judgement </label>
                    <textarea class="form-control" wire:model="primary_diagnosis" rows="2"></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Secondary Investigation (Laboratory Investigation)</label>
                    <textarea class="form-control" wire:model="secondary_diagnosis" rows="2"></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Recommendations</label>
                    <textarea class="form-control" wire:model="recommendations" rows="2" placeholder="Clinic recommendations..."></textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Clinical Judgement</label>
                    <textarea class="form-control" wire:model="clinical_notes" rows="3"></textarea>
                </div>
            </div>
        @endif

        {{-- Step 4: Labs & Treatment Plan --}}
        @if ($step === 4)
            <h5 class="mb-3">Laboratory Tests</h5>
            @foreach ($labTests as $i => $lab)
                <div class="row mb-2 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label">Test Type</label>
                        <input type="text" class="form-control" wire:model="labTests.{{ $i }}.test_type">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Cost</label>
                        <input type="number" step="0.01" class="form-control" wire:model="labTests.{{ $i }}.cost">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger btn-sm" wire:click="removeLabTest({{ $i }})">Remove</button>
                    </div>
                </div>
            @endforeach
            <button type="button" class="btn btn-outline-primary btn-sm mb-3" wire:click="addLabTest">+ Add Lab Test</button>

            <hr>
            <h5 class="mb-3">Medications</h5>
            @foreach ($medications as $i => $med)
                <div class="row mb-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Drug</label>
                        <input type="text" class="form-control" wire:model="medications.{{ $i }}.drug_name">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Qty</label>
                        <input type="number" class="form-control" wire:model="medications.{{ $i }}.quantity">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Unit Cost</label>
                        <input type="number" step="0.01" class="form-control" wire:model="medications.{{ $i }}.unit_cost">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Dosage</label>
                        <input type="text" class="form-control" wire:model="medications.{{ $i }}.dosage">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Duration</label>
                        <input type="text" class="form-control" wire:model="medications.{{ $i }}.duration">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger btn-sm" wire:click="removeMedication({{ $i }})">X</button>
                    </div>
                </div>
            @endforeach
            <button type="button" class="btn btn-outline-primary btn-sm mb-3" wire:click="addMedication">+ Add Medication</button>

            <hr>
            <div class="mb-3">
                <label class="form-label">Treatment Plan</label>
                <textarea class="form-control" wire:model="treatment_plan" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Take-Home Medication Instructions</label>
                <textarea class="form-control" wire:model="take_home_medication" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Treatment Schedule (e.g., 3/7, 5/7, 7/7)</label>
                <input type="text" class="form-control" wire:model="treatment_schedule" placeholder="3/7">
            </div>
        @endif

        {{-- Step 5: Summary & Save --}}
        @if ($step === 5)
            <div class="card mb-3">
                <div class="card-body">
                    <h6>Patient & Visit</h6>
                    <p class="mb-1"><strong>Patient ID:</strong> {{ $patientId }}</p>
                    <p class="mb-1"><strong>Category:</strong> {{ \App\Enums\TreatmentCategory::tryFrom($category)?->label() ?? $category }}
                        @if ($category === 'other' && $other_category)
                            — {{ $other_category }}
                        @endif
                    </p>
                    <p class="mb-1"><strong>Visit Date:</strong> {{ $visit_date }}</p>

                    @if ($presenting_complaint)
                        <h6 class="mt-3">Complaint</h6>
                        <p class="mb-1">{{ $presenting_complaint }}</p>
                    @endif

                    @if ($primary_diagnosis)
                        <h6 class="mt-3">Diagnosis</h6>
                        <p class="mb-1">{{ $primary_diagnosis }}</p>
                    @endif

                    @if ($treatment_plan)
                        <h6 class="mt-3">Treatment Plan</h6>
                        <p class="mb-1">{{ $treatment_plan }}</p>
                    @endif

                    <h6 class="mt-3">Medications: {{ count(array_filter($medications, fn($m) => !empty($m['drug_name']))) }}</h6>
                    <h6>Lab Tests: {{ count(array_filter($labTests, fn($l) => !empty($l['test_type']))) }}</h6>
                </div>
            </div>
        @endif

        <div class="d-flex justify-content-between">
            @if ($step > 1)
                <button type="button" class="btn btn-outline-secondary" wire:click="prevStep">Previous</button>
            @else
                <div></div>
            @endif

            @if ($step < 5)
                <button type="button" class="btn btn-primary" wire:click="nextStep">Next</button>
            @else
                <button type="submit" class="btn btn-success">Save Treatment Chart</button>
            @endif
        </div>
    </form>
</div>
