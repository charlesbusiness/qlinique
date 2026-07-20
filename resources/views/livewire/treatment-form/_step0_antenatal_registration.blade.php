<button type="button" class="btn btn-link btn-sm p-0 mb-3 text-decoration-none" wire:click="goBackToAntenatalOptions">
    &larr; Back to Antenatal Care options
</button>

<h5 class="mb-4">Antenatal Registration</h5>

@if ($registrationSuccess)
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
        </div>
        <h4 class="text-success mb-3">Patient Registered Successfully!</h4>
        <p class="text-muted mb-4">{{ $registeredPatientName }} has been registered as an antenatal patient.</p>
        <button type="button" class="btn btn-primary" wire:click="resetToCategorySelection">
            Back to Categories
        </button>
    </div>
@else
    {{-- Bio Data --}}
    <div class="card mb-4">
        <div class="card-header"><strong>Bio Data</strong></div>
        <div class="card-body">
            {{-- Existing File Option --}}
            <div class="mb-4 p-3 bg-light rounded">
                <label class="form-label fw-semibold">Add to existing file?</label>
                <div class="d-flex gap-4">
                    <div class="form-check">
                        <input type="radio" class="form-check-input" wire:model.live="useExistingFile"
                               value="1" id="useExistingFileYes">
                        <label class="form-check-label fw-bold" for="useExistingFileYes">Yes</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" wire:model.live="useExistingFile"
                               value="0" id="useExistingFileNo">
                        <label class="form-check-label fw-bold" for="useExistingFileNo">No</label>
                    </div>
                </div>
                @if ($useExistingFile == '1')
                    <div class="mt-2">
                        <label class="form-label">Select existing file <span class="text-danger">*</span></label>
                        @php
                            $selectedFile = $existing_file_id ? \App\Models\PatientFile::find($existing_file_id) : null;
                        @endphp

                        @if ($selectedFile)
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-control form-control-sm flex-grow-1">
                                    {{ $selectedFile->file_number }} — {{ $selectedFile->name }}
                                    <span class="badge bg-secondary ms-1">{{ ucfirst($selectedFile->type) }}</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        wire:click="$set('existing_file_id', ''); $set('existingFileResults', [])">
                                    &times;
                                </button>
                            </div>
                        @else
                            <input type="text" class="form-control form-control-sm"
                                   placeholder="Type to search by file number or name..."
                                   wire:input.debounce.300ms="searchPatientFiles($event.target.value)">
                            @if (count($existingFileResults) > 0)
                                <div class="list-group mt-1 shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                    @foreach ($existingFileResults as $file)
                                        <button type="button"
                                                class="list-group-item list-group-item-action py-2"
                                                wire:click="selectExistingFile({{ $file['id'] }})">
                                            <small>{{ $file['label'] }}</small>
                                        </button>
                                    @endforeach
                                </div>
                            @elseif (strlen($search ?? '') >= 2)
                                <div class="text-muted small mt-1">No files found.</div>
                            @endif
                        @endif
                        @error('existing_file_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                @endif
            </div>

            <div class="row g-3">
                {{-- Passport Photo --}}
                <div class="col-md-4">
                    <label class="form-label">Passport Photograph</label>
                    <input type="file" class="form-control @error('reg_photo') is-invalid @enderror"
                           wire:model="reg_photo" accept="image/*">
                    @error('reg_photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if ($reg_photo)
                        <img src="{{ $reg_photo->temporaryUrl() }}" class="mt-2 rounded" style="width: 100px; height: 100px; object-fit: cover;">
                    @endif
                </div>

                {{-- Name --}}
                <div class="col-md-8">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('reg_name') is-invalid @enderror"
                           wire:model="reg_name">
                    @error('reg_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Blood Group --}}
                <div class="col-md-4">
                    <label class="form-label">Blood Group</label>
                    <select class="form-select" wire:model="reg_blood_group">
                        <option value="">-- Select --</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>

                {{-- Genotype --}}
                <div class="col-md-4">
                    <label class="form-label">Genotype</label>
                    <select class="form-select" wire:model="reg_genotype">
                        <option value="">-- Select --</option>
                        <option value="AA">AA</option>
                        <option value="AS">AS</option>
                        <option value="SS">SS</option>
                        <option value="AC">AC</option>
                        <option value="SC">SC</option>
                        <option value="CC">CC</option>
                    </select>
                </div>

                {{-- Gender --}}
                <div class="col-md-4">
                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                    <select class="form-select @error('reg_gender') is-invalid @enderror"
                            wire:model="reg_gender">
                        <option value="">-- Select --</option>
                        <option value="Female">Female</option>
                        <option value="Male">Male</option>
                    </select>
                    @error('reg_gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Date --}}
                <div class="col-md-4">
                    <label class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('reg_date') is-invalid @enderror"
                           wire:model="reg_date">
                    @error('reg_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Phone --}}
                <div class="col-md-4">
                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control @error('reg_phone') is-invalid @enderror"
                           wire:model="reg_phone">
                    @error('reg_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Email --}}
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" wire:model="reg_email">
                </div>

                {{-- Occupation --}}
                <div class="col-md-4">
                    <label class="form-label">Occupation</label>
                    <input type="text" class="form-control" wire:model="reg_occupation">
                </div>

                {{-- Marital Status --}}
                <div class="col-md-4">
                    <label class="form-label">Marital Status</label>
                    <select class="form-select" wire:model="reg_marital_status">
                        <option value="">-- Select --</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Separated">Separated</option>
                    </select>
                </div>

                {{-- Religion --}}
                <div class="col-md-4">
                    <label class="form-label">Religion</label>
                    <select class="form-select" wire:model="reg_religion">
                        <option value="">-- Select --</option>
                        <option value="Christianity">Christianity</option>
                        <option value="Islam">Islam</option>
                        <option value="Traditional">Traditional</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                {{-- Address --}}
                <div class="col-12">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" wire:model="reg_address" rows="2"></textarea>
                </div>

                {{-- Signature --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Patient Signature</label>
                    <div class="mb-2">
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" wire:model.live="reg_signature_type" value="typed" id="reg_sig_typed" autocomplete="off">
                            <label class="btn btn-outline-primary" for="reg_sig_typed">Type</label>
                            <input type="radio" class="btn-check" wire:model.live="reg_signature_type" value="drawn" id="reg_sig_drawn" autocomplete="off">
                            <label class="btn btn-outline-primary" for="reg_sig_drawn">Draw</label>
                            <input type="radio" class="btn-check" wire:model.live="reg_signature_type" value="uploaded" id="reg_sig_upload" autocomplete="off">
                            <label class="btn btn-outline-primary" for="reg_sig_upload">Upload</label>
                        </div>
                    </div>
                    @if ($reg_signature_type === 'typed')
                        <input type="text" class="form-control" wire:model="reg_signature" placeholder="Type signature">
                    @elseif ($reg_signature_type === 'drawn')
                        <div wire:ignore>
                            <div class="border rounded bg-white p-2 mb-2" style="position:relative;">
                                <canvas id="reg-pat-sig-canvas" width="450" height="150" style="width:100%;height:150px;border:1px solid #dee2e6;cursor:crosshair;"></canvas>
                                <div style="position:absolute;bottom:8px;right:8px;display:flex;gap:6px;">
                                    <button type="button" class="btn btn-sm btn-outline-danger py-0" onclick="window.dispatchEvent(new CustomEvent('clear-reg-pat-canvas'))">Clear</button>
                                </div>
                            </div>
                        </div>
                    @elseif ($reg_signature_type === 'uploaded')
                        <input type="file" class="form-control" wire:model="reg_signature_upload" accept="image/*">
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" wire:model="reg_signature_name">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" wire:model="reg_signature_date">
                </div>
            </div>
        </div>
    </div>

    {{-- Next of Kin --}}
    <div class="card mb-4">
        <div class="card-header"><strong>Next of Kin</strong></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" wire:model="nok_name">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Relationship</label>
                    <input type="text" class="form-control" wire:model="nok_relationship">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="tel" class="form-control" wire:model="nok_phone">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" wire:model="nok_address" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Consent --}}
    <div class="card mb-4">
        <div class="card-header"><strong>Consent</strong></div>
        <div class="card-body">
            <div class="form-check mb-2">
                <input type="checkbox" class="form-check-input @error('nok_consent') is-invalid @enderror"
                       wire:model="nok_consent" id="consentCheck">
                <label class="form-check-label" for="consentCheck">Consent <span class="text-danger">*</span></label>
                @error('nok_consent') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input @error('nok_privacy_consent') is-invalid @enderror"
                       wire:model="nok_privacy_consent" id="privacyCheck">
                <label class="form-check-label" for="privacyCheck">Data Privacy Consent <span class="text-danger">*</span></label>
                @error('nok_privacy_consent') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Next of Kin Signature</label>
                    <div class="mb-2">
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" wire:model.live="nok_signature_type" value="typed" id="nok_sig_typed" autocomplete="off">
                            <label class="btn btn-outline-primary" for="nok_sig_typed">Type</label>

                            <input type="radio" class="btn-check" wire:model.live="nok_signature_type" value="drawn" id="nok_sig_drawn" autocomplete="off">
                            <label class="btn btn-outline-primary" for="nok_sig_drawn">Draw</label>

                            <input type="radio" class="btn-check" wire:model.live="nok_signature_type" value="uploaded" id="nok_sig_upload" autocomplete="off">
                            <label class="btn btn-outline-primary" for="nok_sig_upload">Upload</label>
                        </div>
                    </div>

                    @if ($nok_signature_type === 'typed')
                        <input type="text" class="form-control" wire:model="nok_signature" placeholder="Type full name as signature">
                        @if ($nok_signature)
                            <div class="mt-2 p-3 border rounded bg-light" style="font-family: 'Dancing Script', 'Pacifico', cursive; font-size: 1.5rem;">
                                {{ $nok_signature }}
                            </div>
                        @endif
                    @elseif ($nok_signature_type === 'drawn')
                        <div wire:ignore>
                            <div class="border rounded bg-white p-2 mb-2" style="position:relative;">
                                <canvas id="nok-signature-canvas" width="450" height="150" style="width:100%;height:150px;border:1px solid #dee2e6;cursor:crosshair;"></canvas>
                                <div style="position:absolute;bottom:8px;right:8px;display:flex;gap:6px;">
                                    <button type="button" class="btn btn-sm btn-outline-danger py-0" onclick="window.dispatchEvent(new CustomEvent('clear-nok-canvas'))">Clear</button>
                                </div>
                            </div>
                        </div>
                    @elseif ($nok_signature_type === 'uploaded')
                        <input type="file" class="form-control" wire:model="nok_signature_upload" accept="image/*">
                        @if ($nok_signature_upload)
                            <div class="mt-2">
                                <img src="{{ $nok_signature_upload->temporaryUrl() }}" class="border rounded" style="max-height: 80px;">
                            </div>
                        @endif
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" wire:model="nok_date">
                </div>
            </div>
        </div>
    </div>

    {{-- Submit Button --}}
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-success btn-lg" wire:click="submitAntenatalRegistration"
                wire:loading.attr="disabled">
            <span wire:loading.remove>Register Patient</span>
            <span wire:loading><i class="bi bi-hourglass-split"></i> Registering...</span>
        </button>
    </div>
@endif
