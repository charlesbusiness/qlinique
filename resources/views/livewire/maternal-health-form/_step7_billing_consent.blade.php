<h6 class="mb-3">Informed Consent</h6>
<div class="mb-3">
    <div class="form-check form-switch mb-2">
        <input type="checkbox" class="form-check-input" wire:model="consent_enabled" id="consent_toggle">
        <label class="form-check-label" for="consent_toggle">Enable Informed Consent</label>
    </div>
</div>

<hr class="my-4">

<h6 class="mb-3">Referral Letter</h6>
<div class="mb-3">
    <div class="form-check form-switch">
        <input type="checkbox" class="form-check-input" wire:model="referral_letter" id="referral_toggle">
        <label class="form-check-label" for="referral_toggle">Include Referral Letter</label>
    </div>
</div>

<hr class="my-4">

<h6 class="mb-3">Medical Bill</h6>
<div class="table-responsive mb-3">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>S/N</th>
                <th>Item</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach (['registration' => 'Registration', 'consultation' => 'Consultation', 'rapid_medical_examination' => 'RME', 'laboratory_test' => 'Laboratory Test', 'admission' => 'Admission', 'medical_service' => 'Medical Service', 'logistics' => 'Logistics', 'maintenance' => 'Maintenance', 'surgical_procedure' => 'Surgical Procedure'] as $key => $label)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $label }}</td>
                    <td>
                        <input type="number" step="0.01" class="form-control form-control-sm" wire:model="medical_bill.{{ $key }}">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label fw-semibold">Paid</label>
        <input type="number" step="0.01" class="form-control" wire:model="bill_paid">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Outstanding</label>
        <input type="number" step="0.01" class="form-control" wire:model="bill_outstanding">
    </div>
</div>

<hr class="my-4">

<h6 class="mb-3">Next Visit (Appointment)</h6>
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Next Visit Date</label>
        <input type="date" class="form-control" wire:model="next_visit_date">
    </div>
    <div class="col-md-4">
        <label class="form-label">Attending Physician</label>
        <input type="text" class="form-control" wire:model="attending_physician_name">
    </div>
    <div class="col-md-4">
        <label class="form-label">Physician Signature</label>
        <input type="text" class="form-control" wire:model="attending_physician_signature">
    </div>
    <div class="col-md-4">
        <label class="form-label">Date</label>
        <input type="date" class="form-control" wire:model="attending_physician_date">
    </div>
</div>
