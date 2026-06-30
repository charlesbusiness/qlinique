<div>
    <div class="mb-4">
        <div class="d-flex gap-2 flex-wrap">
            @foreach ([1 => 'Patient', 2 => 'Vitals', 3 => 'History & Diagnosis', 4 => 'Medications & Plan', 5 => 'Consent', 6 => 'Summary'] as $num => $label)
                <span class="badge {{ $step >= $num ? 'bg-primary' : 'bg-secondary' }} fs-6 px-3 py-2 {{ $step === $num ? '' : 'd-none d-md-inline' }}">{{ $num }}. {{ $label }}</span>
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

                    @php
                        $subOptions = \App\Livewire\TreatmentForm::subCategoryOptions($category);
                    @endphp
                    @if ($subOptions)
                        <div class="mt-3">
                            <label class="form-label">Sub-category <span class="text-danger">*</span></label>
                            @error('sub_category') <div class="text-danger small mb-1">{{ $message }}</div> @enderror
                            @foreach ($subOptions as $value => $label)
                                <div class="form-check">
                                    <input type="radio" class="form-check-input @error('sub_category') is-invalid @enderror"
                                           wire:model="sub_category" value="{{ $value }}" id="sub_{{ $value }}">
                                    <label class="form-check-label" for="sub_{{ $value }}">{{ $label }}</label>
                                </div>
                            @endforeach
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
                        <input type="number" step="0.1" class="form-control @error('vitals.temperature') is-invalid @enderror" wire:model="vitals.temperature">
                        <select class="form-select" style="max-width: 80px;" wire:model="vitals.temperature_unit">
                            <option value="celsius">°C</option>
                            <option value="fahrenheit">°F</option>
                        </select>
                    </div>
                    @error('vitals.temperature') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Blood Pressure (Systolic) (mmHg)</label>
                    <input type="number" class="form-control @error('vitals.blood_pressure_systolic') is-invalid @enderror" wire:model="vitals.blood_pressure_systolic">
                    @error('vitals.blood_pressure_systolic') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Blood Pressure (Diastolic) (mmHg)</label>
                    <input type="number" class="form-control @error('vitals.blood_pressure_diastolic') is-invalid @enderror" wire:model="vitals.blood_pressure_diastolic">
                    @error('vitals.blood_pressure_diastolic') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Pulse Rate (bpm)</label>
                    <input type="number" class="form-control @error('vitals.pulse_rate') is-invalid @enderror" wire:model="vitals.pulse_rate">
                    @error('vitals.pulse_rate') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Respiratory Rate (bpm)</label>
                    <input type="number" class="form-control @error('vitals.respiratory_rate') is-invalid @enderror" wire:model="vitals.respiratory_rate">
                    @error('vitals.respiratory_rate') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Oxygen Saturation (%)</label>
                    <input type="number" class="form-control @error('vitals.oxygen_saturation') is-invalid @enderror" wire:model="vitals.oxygen_saturation">
                    @error('vitals.oxygen_saturation') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Weight (kg)</label>
                    <input type="number" step="0.1" class="form-control @error('vitals.weight') is-invalid @enderror" wire:model="vitals.weight">
                    @error('vitals.weight') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Height (cm)</label>
                    <input type="number" step="0.1" class="form-control @error('vitals.height') is-invalid @enderror" wire:model="vitals.height">
                    @error('vitals.height') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">BMI</label>
                    <input type="number" step="0.1" class="form-control @error('vitals.bmi') is-invalid @enderror" wire:model="vitals.bmi" readonly>
                    @error('vitals.bmi') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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
                    <label class="form-label">Secondary Investigation (Lab Tests Required)</label>
                    <textarea class="form-control" wire:model="secondary_diagnosis" rows="2"></textarea>
                </div>
                <div class="col-md-12 mb-3">
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
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Clinical Notes</label>
                    <textarea class="form-control" wire:model="clinical_notes" rows="3"></textarea>
                </div>
            </div>
        @endif

        {{-- Step 4: Medications & Plan --}}
        @if ($step === 4)
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
                <div class="input-group">
                    <input type="number" class="form-control" wire:model="treatment_plan_value" placeholder="Value">
                    <select class="form-select" style="max-width: 160px;" wire:model="treatment_plan_type">
                        <option value="days">Days</option>
                        <option value="weeks">Weeks</option>
                        <option value="months">Months</option>
                    </select>
                    <span class="input-group-text">
                        {{ $treatment_plan_value ? $treatment_plan_value . ' ' . $treatment_plan_type : '—' }}
                    </span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Take-Home Medication Instructions</label>
                <textarea class="form-control" wire:model="take_home_medication" rows="2"></textarea>
            </div>
        @endif

        {{-- Step 5: Consent --}}
        @if ($step === 5)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Informed Consent</h5>
                <div class="alert alert-info">
                    <p class="mb-0"><strong>INFORMED MEDICAL CONSENT</strong></p>
                    <p class="mb-0 mt-2">I hereby give my consent for the medical practitioners, physicians, and authorized medical staff of CORNERSTONE CAREPOINT CLINIC to perform the following medical procedure(s) or treatment(s) as required by my health condition.</p>
                    <p class="mb-0 mt-2">I acknowledge that the nature, purpose, risks, and benefits of the procedure have been explained to me in a language I understand. I have been informed of viable alternative treatments and the risks of refusing treatment. I authorize the medical staff to perform any additional or alternative procedures deemed medically necessary. I am signing this form voluntarily, of sound mind, and have had all my questions answered.</p>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Name/Description of Procedure <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('consent.procedure_description') is-invalid @enderror" wire:model="consent.procedure_description" rows="2" placeholder="Describe the procedure or treatment..."></textarea>
                        @error('consent.procedure_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Attending Physician <span class="text-danger">*</span></label>
                        <select class="form-select @error('consent.attending_physician') is-invalid @enderror" wire:model="consent.attending_physician">
                            <option value="">— Select —</option>
                            @foreach ($staff as $s)
                                <option value="{{ $s->name }}">{{ $s->name }} ({{ ucfirst($s->role) }})</option>
                            @endforeach
                        </select>
                        @error('consent.attending_physician') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr>
                <h5 class="mb-3">Signatures</h5>

                {{-- Patient / Representative Signature --}}
                <div class="card mb-3 bg-light">
                    <div class="card-body">
                        <h6>Patient / Authorized Representative <span class="text-danger">*</span></h6>
                        <div class="mb-3">
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" wire:model.live="consent.patient_signature_type" value="typed" id="pat_sig_typed" autocomplete="off">
                                <label class="btn btn-outline-primary btn-sm" for="pat_sig_typed">Type</label>
                                <input type="radio" class="btn-check" wire:model.live="consent.patient_signature_type" value="uploaded" id="pat_sig_upload" autocomplete="off">
                                <label class="btn btn-outline-primary btn-sm" for="pat_sig_upload">Upload</label>
                            </div>
                        </div>
                        @error('consent.patient_signature_type') <div class="text-danger small">{{ $message }}</div> @enderror

                        @if ($consent['patient_signature_type'] === 'typed')
                        <div class="mb-2">
                            <label class="form-label">Type Full Name</label>
                            <input type="text" class="form-control @error('consent.patient_signature') is-invalid @enderror" wire:model="consent.patient_signature" placeholder="e.g. John Doe">
                            @error('consent.patient_signature') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if ($consent['patient_signature'])
                            <div class="mt-2 p-2 border rounded bg-white" style="font-family: 'Dancing Script', 'Pacifico', cursive; font-size: 1.5rem;">
                                {{ $consent['patient_signature'] }}
                            </div>
                            @endif
                        </div>
                        @elseif ($consent['patient_signature_type'] === 'uploaded')
                        <div class="mb-2">
                            <label class="form-label">Upload Signature Image</label>
                            <input type="file" class="form-control @error('consent_upload_patient') is-invalid @enderror" wire:model="consent_upload_patient" accept="image/*">
                            @error('consent_upload_patient') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if ($consent_upload_patient)
                            <div class="mt-2">
                                <img src="{{ $consent_upload_patient->temporaryUrl() }}" class="border rounded" style="max-height: 60px;">
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Witness Signature --}}
                <div class="card mb-3 bg-light">
                    <div class="card-body">
                        <h6>Witness <span class="text-danger">*</span></h6>
                        <div class="mb-2">
                            <label class="form-label">Witness Full Name</label>
                            <input type="text" class="form-control @error('consent.witness_name') is-invalid @enderror" wire:model="consent.witness_name" placeholder="e.g. Jane Smith">
                            @error('consent.witness_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" wire:model.live="consent.witness_signature_type" value="typed" id="wit_sig_typed" autocomplete="off">
                                <label class="btn btn-outline-primary btn-sm" for="wit_sig_typed">Type</label>
                                <input type="radio" class="btn-check" wire:model.live="consent.witness_signature_type" value="uploaded" id="wit_sig_upload" autocomplete="off">
                                <label class="btn btn-outline-primary btn-sm" for="wit_sig_upload">Upload</label>
                            </div>
                        </div>
                        @error('consent.witness_signature_type') <div class="text-danger small">{{ $message }}</div> @enderror

                        @if ($consent['witness_signature_type'] === 'typed')
                        <div class="mb-2">
                            <label class="form-label">Type Full Name</label>
                            <input type="text" class="form-control @error('consent.witness_signature') is-invalid @enderror" wire:model="consent.witness_signature" placeholder="e.g. Jane Smith">
                            @error('consent.witness_signature') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if ($consent['witness_signature'])
                            <div class="mt-2 p-2 border rounded bg-white" style="font-family: 'Dancing Script', 'Pacifico', cursive; font-size: 1.5rem;">
                                {{ $consent['witness_signature'] }}
                            </div>
                            @endif
                        </div>
                        @elseif ($consent['witness_signature_type'] === 'uploaded')
                        <div class="mb-2">
                            <label class="form-label">Upload Signature Image</label>
                            <input type="file" class="form-control @error('consent_upload_witness') is-invalid @enderror" wire:model="consent_upload_witness" accept="image/*">
                            @error('consent_upload_witness') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if ($consent_upload_witness)
                            <div class="mt-2">
                                <img src="{{ $consent_upload_witness->temporaryUrl() }}" class="border rounded" style="max-height: 60px;">
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Physician Signature --}}
                <div class="card mb-3 bg-light">
                    <div class="card-body">
                        <h6>Physician / Healthcare Provider <span class="text-danger">*</span></h6>
                        <div class="mb-3">
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" wire:model.live="consent.physician_signature_type" value="typed" id="doc_sig_typed" autocomplete="off">
                                <label class="btn btn-outline-primary btn-sm" for="doc_sig_typed">Type</label>
                                <input type="radio" class="btn-check" wire:model.live="consent.physician_signature_type" value="uploaded" id="doc_sig_upload" autocomplete="off">
                                <label class="btn btn-outline-primary btn-sm" for="doc_sig_upload">Upload</label>
                            </div>
                        </div>
                        @error('consent.physician_signature_type') <div class="text-danger small">{{ $message }}</div> @enderror

                        @if ($consent['physician_signature_type'] === 'typed')
                        <div class="mb-2">
                            <label class="form-label">Type Full Name</label>
                            <input type="text" class="form-control @error('consent.physician_signature') is-invalid @enderror" wire:model="consent.physician_signature" placeholder="e.g. Dr. John Doe">
                            @error('consent.physician_signature') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text mt-1">I confirm that I have explained the nature, purpose, risks, and benefits of the aforementioned procedure to the patient and have answered any questions raised.</div>
                            @if ($consent['physician_signature'])
                            <div class="mt-2 p-2 border rounded bg-white" style="font-family: 'Dancing Script', 'Pacifico', cursive; font-size: 1.5rem;">
                                {{ $consent['physician_signature'] }}
                            </div>
                            @endif
                        </div>
                        @elseif ($consent['physician_signature_type'] === 'uploaded')
                        <div class="mb-2">
                            <label class="form-label">Upload Signature Image</label>
                            <input type="file" class="form-control @error('consent_upload_physician') is-invalid @enderror" wire:model="consent_upload_physician" accept="image/*">
                            @error('consent_upload_physician') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if ($consent_upload_physician)
                            <div class="mt-2">
                                <img src="{{ $consent_upload_physician->temporaryUrl() }}" class="border rounded" style="max-height: 60px;">
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Step 6: Summary & Save --}}
        @if ($step === 6)
            <div class="card mb-3">
                <div class="card-body">
                    <h6>Patient & Visit</h6>
                    <p class="mb-1"><strong>Patient ID:</strong> {{ $patientId }}</p>
                    <p class="mb-1"><strong>Category:</strong> {{ \App\Enums\TreatmentCategory::tryFrom($category)?->label() ?? $category }}
                        @if ($category === 'other' && $other_category)
                            — {{ $other_category }}
                        @elseif ($sub_category)
                            — {{ (\App\Livewire\TreatmentForm::subCategoryOptions($category))[$sub_category] ?? $sub_category }}
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

                    @if ($treatment_plan_value)
                        <h6 class="mt-3">Treatment Plan</h6>
                        <p class="mb-1">{{ $treatment_plan_value }} / {{ ['days' => 7, 'weeks' => 52, 'months' => 12][$treatment_plan_type] ?? 7 }} ({{ ucfirst($treatment_plan_type) }})</p>
                    @endif
                    <h6 class="mt-3">Medications: {{ count(array_filter($medications, fn($m) => !empty($m['drug_name']))) }}</h6>
                    <h6>Lab Tests: {{ count(array_filter($labTests, fn($l) => !empty($l['test_type']))) }}</h6>
                </div>
            </div>

            @if ($consent['procedure_description'])
            <div class="card mb-3">
                <div class="card-body">
                    <h6>Consent</h6>
                    <p class="mb-1"><strong>Procedure:</strong> {{ $consent['procedure_description'] }}</p>
                    <p class="mb-1"><strong>Attending Physician:</strong> {{ $consent['attending_physician'] }}</p>
                    <p class="mb-1"><strong>Patient Signature:</strong>
                        @if ($consent['patient_signature_type'] === 'typed' && $consent['patient_signature'])
                            {{ $consent['patient_signature'] }}
                        @elseif ($consent['patient_signature_type'] === 'uploaded')
                            <span class="badge bg-info">Uploaded</span>
                        @endif
                    </p>
                    <p class="mb-1"><strong>Witness:</strong> {{ $consent['witness_name'] ?: '—' }}
                        @if ($consent['witness_signature_type'] === 'typed' && $consent['witness_signature'])
                            ({{ $consent['witness_signature'] }})
                        @elseif ($consent['witness_signature_type'] === 'uploaded')
                            <span class="badge bg-info">Uploaded</span>
                        @endif
                    </p>
                    <p class="mb-0"><strong>Physician Signature:</strong>
                        @if ($consent['physician_signature_type'] === 'typed' && $consent['physician_signature'])
                            {{ $consent['physician_signature'] }}
                        @elseif ($consent['physician_signature_type'] === 'uploaded')
                            <span class="badge bg-info">Uploaded</span>
                        @endif
                    </p>
                </div>
            </div>
            @endif
        @endif

        <div class="d-flex justify-content-between">
            @if ($step > 1)
                <button type="button" class="btn btn-outline-secondary" wire:click="prevStep">Previous</button>
            @else
                <div></div>
            @endif

            @if ($step < 6)
                <button type="button" class="btn btn-primary" wire:click="nextStep">Next</button>
            @else
                <button type="submit" class="btn btn-success">Save Treatment Chart</button>
            @endif
        </div>
    </form>
</div>
