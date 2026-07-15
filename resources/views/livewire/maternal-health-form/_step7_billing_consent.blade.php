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
    @php
        $calculatedItems = [
            'laboratory_test' => 'Laboratory Test',
            'medical_service' => 'Medical Service',
        ];
        $manualItems = [
            'registration' => 'Registration',
            'consultation' => 'Consultation',
            'rapid_medical_examination' => 'RME',
            'admission' => 'Admission',
            'logistics' => 'Logistics',
            'maintenance' => 'Maintenance',
            'surgical_procedure' => 'Surgical Procedure',
        ];
    @endphp
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>S/N</th>
                <th>Item</th>
                <th>Amount (₦)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($calculatedItems as $key => $label)
                <tr class="table-secondary">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $label }}</td>
                    <td>
                        <span class="form-control form-control-sm bg-light border-0 fw-bold">{{ number_format($medical_bill[$key] ?? 0, 2) }}</span>
                    </td>
                </tr>
            @endforeach
            @foreach ($manualItems as $key => $label)
                <tr>
                    <td>{{ $loop->iteration + count($calculatedItems) }}</td>
                    <td>{{ $label }}</td>
                    <td>
                        <input type="number" step="0.01" class="form-control form-control-sm" wire:model="medical_bill.{{ $key }}">
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="fw-bold">
                <td colspan="2" class="text-end">TOTAL ₦</td>
                <td>{{ number_format(collect($medical_bill)->sum(), 2) }}</td>
            </tr>
            <tr>
                <td colspan="2" class="text-end fw-bold">Paid Bill ₦</td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" wire:model.live="bill_paid" placeholder="0.00"></td>
            </tr>
            <tr class="fw-bold">
                <td colspan="2" class="text-end">Balance ₦</td>
                <td>{{ number_format($bill_outstanding, 2) }}</td>
            </tr>
        </tfoot>
    </table>
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
        <select class="form-select" wire:model="attending_physician_name">
            <option value="">Select...</option>
            @foreach ($staff as $s)
                <option value="{{ $s->name }}">{{ $s->name }} ({{ ucfirst(str_replace('_', ' ', $s->role)) }})</option>
            @endforeach
        </select>
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
