<div>
    <form wire:submit="save">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Gender</label>
                <select class="form-select @error('gender') is-invalid @enderror" wire:model="gender">
                    <option value="">Select...</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Date of Birth</label>
                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" wire:model="date_of_birth">
                @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Phone</label>
                <input type="text" class="form-control" wire:model="phone">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" wire:model="email">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Occupation</label>
                <input type="text" class="form-control" wire:model="occupation">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Marital Status</label>
                <select class="form-select" wire:model="marital_status">
                    <option value="">Select...</option>
                    <option value="single">Single</option>
                    <option value="married">Married</option>
                    <option value="divorced">Divorced</option>
                    <option value="widowed">Widowed</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Account Type</label>
                <select class="form-select @error('account_type') is-invalid @enderror" wire:model="account_type">
                    <option value="individual">Individual</option>
                    <option value="family">Family</option>
                    <option value="corporate">Corporate</option>
                </select>
                @error('account_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea class="form-control" wire:model="address" rows="2"></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Religion</label>
                <input type="text" class="form-control" wire:model="religion">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Denomination</label>
                <input type="text" class="form-control" wire:model="denomination">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Photo</label>
            @if ($existingPhoto)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $existingPhoto) }}" class="rounded" style="max-height: 100px;">
                </div>
            @endif
            <input type="file" class="form-control @error('photo') is-invalid @enderror" wire:model="photo">
            @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <hr>
        <h5 class="mb-3">Next of Kin</h5>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" wire:model="next_of_kin.name">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Relationship</label>
                <input type="text" class="form-control" wire:model="next_of_kin.relationship">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Phone</label>
                <input type="text" class="form-control" wire:model="next_of_kin.phone">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea class="form-control" wire:model="next_of_kin.address" rows="2"></textarea>
        </div>

        <hr>
        <h5 class="mb-3">Consent</h5>

        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" wire:model="consent.treatment" id="consent_treatment">
            <label class="form-check-label" for="consent_treatment">Treatment Consent</label>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" wire:model="consent.privacy" id="consent_privacy">
            <label class="form-check-label" for="consent_privacy">Data Privacy Consent</label>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                {{ $patient ? 'Update Patient' : 'Register Patient' }}
            </button>
        </div>
    </form>
</div>
