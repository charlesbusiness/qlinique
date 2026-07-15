<div class="mb-4">
    <h6 class="text-muted mb-3">Patient Information (from record)</h6>

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label fw-semibold">Name</label>
            <p>{{ $patient?->name ?? '—' }}</p>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">File Number</label>
            <p>{{ $patient?->file?->file_number ?? '—' }}</p>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Gender</label>
            <p>{{ $patient?->gender ?? '—' }}</p>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Date of Birth</label>
            <p>{{ $patient?->date_of_birth?->format('d M Y') ?? '—' }}</p>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Phone</label>
            <p>{{ $patient?->phone ?? '—' }}</p>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Email</label>
            <p>{{ $patient?->email ?? '—' }}</p>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Blood Group</label>
            <p>{{ $patient?->blood_group ?? '—' }}</p>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Genotype</label>
            <p>{{ $patient?->genotype ?? '—' }}</p>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Marital Status</label>
            <p>{{ $patient?->marital_status ?? '—' }}</p>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Occupation</label>
            <p>{{ $patient?->occupation ?? '—' }}</p>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Religion</label>
            <p>{{ $patient?->religion ?? '—' }}</p>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Address</label>
            <p>{{ $patient?->address ?? '—' }}</p>
        </div>
    </div>

    @if ($patient?->next_of_kin)
        <hr>
        <h6 class="text-muted mb-3">Next of Kin</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Name</label>
                <p>{{ $patient->next_of_kin['name'] ?? '—' }}</p>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Relationship</label>
                <p>{{ $patient->next_of_kin['relationship'] ?? '—' }}</p>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Phone</label>
                <p>{{ $patient->next_of_kin['phone'] ?? '—' }}</p>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Address</label>
                <p>{{ $patient->next_of_kin['address'] ?? '—' }}</p>
            </div>
        </div>
    @endif
</div>
