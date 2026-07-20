<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">INFORMED CONSENT</h5>
        <div class="mb-3">
            <label class="form-label">Is Informed Consent Required?</label>
            <div class="d-flex gap-3">
                <div class="form-check">
                    <input type="radio" class="form-check-input" wire:model.live="consent_enabled" value="1" id="consent_yes">
                    <label class="form-check-label" for="consent_yes">YES</label>
                </div>
                <div class="form-check">
                    <input type="radio" class="form-check-input" wire:model.live="consent_enabled" value="0" id="consent_no">
                    <label class="form-check-label" for="consent_no">NO</label>
                </div>
            </div>
        </div>

        @if ($consent_enabled)
            <div class="alert alert-info small">
                <p class="mb-0"><strong>INFORMED MEDICAL CONSENT</strong></p>
                <p class="mb-0 mt-2">I hereby give my consent for the medical practitioners, physicians, and authorized medical staff of CORNERSTONE CAREPOINT CLINIC to perform the following medical procedure(s) or treatment(s) as required by my health condition.</p>
            </div>

            <div class="mb-3">
                <label class="form-label">Name/Description of Procedure <span class="text-danger">*</span></label>
                <textarea class="form-control @error('consent.procedure_description') is-invalid @enderror" wire:model="consent.procedure_description" rows="2"></textarea>
                @error('consent.procedure_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Attending Physician <span class="text-danger">*</span></label>
                <select class="form-select @error('consent.attending_physician') is-invalid @enderror" wire:model="consent.attending_physician">
                    <option value="">— Select —</option>
                    @foreach ($staff as $s)
                        <option value="{{ $s->name }}">{{ $s->name }} ({{ ucfirst($s->role) }})</option>
                    @endforeach
                </select>
                @error('consent.attending_physician') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <hr>
            <h6>Signatures</h6>

            {{-- Patient / Authorized Representative --}}
            <div class="card mb-3 bg-light">
                <div class="card-body">
                    <h6>Patient / Authorized Representative <span class="text-danger">*</span></h6>
                    <div class="mb-2">
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" wire:change="setConsentSigType('patient_signature_type', 'typed')" value="typed" id="pat_sig_typed" autocomplete="off" {{ ($consent['patient_signature_type'] ?? '') === 'typed' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary btn-sm" for="pat_sig_typed">Type</label>
                            <input type="radio" class="btn-check" wire:change="setConsentSigType('patient_signature_type', 'drawn')" value="drawn" id="pat_sig_drawn" autocomplete="off" {{ ($consent['patient_signature_type'] ?? '') === 'drawn' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary btn-sm" for="pat_sig_drawn">Draw</label>
                            <input type="radio" class="btn-check" wire:change="setConsentSigType('patient_signature_type', 'uploaded')" value="uploaded" id="pat_sig_upload" autocomplete="off" {{ ($consent['patient_signature_type'] ?? '') === 'uploaded' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary btn-sm" for="pat_sig_upload">Upload</label>
                        </div>
                        @error('consent.patient_signature_type') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    @if ($consent['patient_signature_type'] === 'typed')
                        <input type="text" class="form-control @error('consent.patient_signature') is-invalid @enderror" wire:model="consent.patient_signature" placeholder="Type full name">
                        @error('consent.patient_signature') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @elseif ($consent['patient_signature_type'] === 'drawn')
                        <div class="border rounded bg-white p-2 mb-2" style="position:relative;">
                            <canvas id="consent-pat-sig-canvas" width="450" height="150" style="width:100%;height:150px;border:1px solid #dee2e6;cursor:crosshair;"></canvas>
                            @error('consent_drawn_patient') <div class="text-danger small">{{ $message }}</div> @enderror
                            <div style="position:absolute;bottom:8px;right:8px;display:flex;gap:6px;">
                                <button type="button" class="btn btn-sm btn-outline-danger py-0" onclick="window.dispatchEvent(new CustomEvent('clear-consent-pat-canvas'))">Clear</button>
                            </div>
                        </div>
                    @elseif ($consent['patient_signature_type'] === 'uploaded')
                        <input type="file" class="form-control @error('consent_upload_patient') is-invalid @enderror" wire:model="consent_upload_patient" accept="image/*">
                        @error('consent_upload_patient') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @endif
                </div>
            </div>

            {{-- Witness --}}
            <div class="card mb-3 bg-light">
                <div class="card-body">
                    <h6>Witness <span class="text-danger">*</span></h6>
                    <div class="mb-2">
                        <input type="text" class="form-control @error('consent.witness_name') is-invalid @enderror" wire:model="consent.witness_name" placeholder="Witness full name">
                        @error('consent.witness_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-2">
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" wire:change="setConsentSigType('witness_signature_type', 'typed')" value="typed" id="wit_sig_typed" autocomplete="off" {{ ($consent['witness_signature_type'] ?? '') === 'typed' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary btn-sm" for="wit_sig_typed">Type</label>
                            <input type="radio" class="btn-check" wire:change="setConsentSigType('witness_signature_type', 'drawn')" value="drawn" id="wit_sig_drawn" autocomplete="off" {{ ($consent['witness_signature_type'] ?? '') === 'drawn' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary btn-sm" for="wit_sig_drawn">Draw</label>
                            <input type="radio" class="btn-check" wire:change="setConsentSigType('witness_signature_type', 'uploaded')" value="uploaded" id="wit_sig_upload" autocomplete="off" {{ ($consent['witness_signature_type'] ?? '') === 'uploaded' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary btn-sm" for="wit_sig_upload">Upload</label>
                        </div>
                    </div>
                    @if ($consent['witness_signature_type'] === 'typed')
                        <input type="text" class="form-control @error('consent.witness_signature') is-invalid @enderror" wire:model="consent.witness_signature" placeholder="Type signature">
                        @error('consent.witness_signature') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @elseif ($consent['witness_signature_type'] === 'drawn')
                        <div class="border rounded bg-white p-2 mb-2" style="position:relative;">
                            <canvas id="consent-wit-sig-canvas" width="450" height="150" style="width:100%;height:150px;border:1px solid #dee2e6;cursor:crosshair;"></canvas>
                            @error('consent_drawn_witness') <div class="text-danger small">{{ $message }}</div> @enderror
                            <div style="position:absolute;bottom:8px;right:8px;display:flex;gap:6px;">
                                <button type="button" class="btn btn-sm btn-outline-danger py-0" onclick="window.dispatchEvent(new CustomEvent('clear-consent-wit-canvas'))">Clear</button>
                            </div>
                        </div>
                    @elseif ($consent['witness_signature_type'] === 'uploaded')
                        <input type="file" class="form-control @error('consent_upload_witness') is-invalid @enderror" wire:model="consent_upload_witness" accept="image/*">
                        @error('consent_upload_witness') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @endif
                </div>
            </div>

            {{-- Physician / Healthcare Provider --}}
            <div class="card mb-3 bg-light">
                <div class="card-body">
                    <h6>Physician / Healthcare Provider <span class="text-danger">*</span></h6>
                    <div class="mb-2">
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" wire:change="setConsentSigType('physician_signature_type', 'typed')" value="typed" id="doc_sig_typed" autocomplete="off" {{ ($consent['physician_signature_type'] ?? '') === 'typed' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary btn-sm" for="doc_sig_typed">Type</label>
                            <input type="radio" class="btn-check" wire:change="setConsentSigType('physician_signature_type', 'drawn')" value="drawn" id="doc_sig_drawn" autocomplete="off" {{ ($consent['physician_signature_type'] ?? '') === 'drawn' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary btn-sm" for="doc_sig_drawn">Draw</label>
                            <input type="radio" class="btn-check" wire:change="setConsentSigType('physician_signature_type', 'uploaded')" value="uploaded" id="doc_sig_upload" autocomplete="off" {{ ($consent['physician_signature_type'] ?? '') === 'uploaded' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary btn-sm" for="doc_sig_upload">Upload</label>
                        </div>
                    </div>
                    @if ($consent['physician_signature_type'] === 'typed')
                        <input type="text" class="form-control @error('consent.physician_signature') is-invalid @enderror" wire:model="consent.physician_signature" placeholder="Type full name">
                        @error('consent.physician_signature') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text small">I confirm that I have explained the nature, purpose, risks, and benefits of the aforementioned procedure to the patient.</div>
                    @elseif ($consent['physician_signature_type'] === 'drawn')
                        <div class="border rounded bg-white p-2 mb-2" style="position:relative;">
                            <canvas id="consent-doc-sig-canvas" width="450" height="150" style="width:100%;height:150px;border:1px solid #dee2e6;cursor:crosshair;"></canvas>
                            @error('consent_drawn_physician') <div class="text-danger small">{{ $message }}</div> @enderror
                            <div style="position:absolute;bottom:8px;right:8px;display:flex;gap:6px;">
                                <button type="button" class="btn btn-sm btn-outline-danger py-0" onclick="window.dispatchEvent(new CustomEvent('clear-consent-doc-canvas'))">Clear</button>
                            </div>
                        </div>
                        <div class="form-text small">I confirm that I have explained the nature, purpose, risks, and benefits of the aforementioned procedure to the patient.</div>
                    @elseif ($consent['physician_signature_type'] === 'uploaded')
                        <input type="file" class="form-control @error('consent_upload_physician') is-invalid @enderror" wire:model="consent_upload_physician" accept="image/*">
                        @error('consent_upload_physician') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
