@php
    $billItems = [
        'registration' => 'Registration Bill',
        'consultation' => 'Consultation Bill',
        'admission' => 'Admission Bill',
        'logistics' => 'Logistics Bill',
        'maintenance' => 'Maintenance Bill',
        'surgical_procedure' => 'Surgical Procedure Bill',
    ];
    $calculatedItems = [
        'rapid_medical_examination' => 'Rapid Medical Examination Bill',
        'laboratory_test' => 'Laboratory Test Bill',
        'medical_service' => 'Medical Service Bill',
    ];
@endphp
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">MEDICAL BILL</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>NAME</th>
                        <th>AMOUNT (₦)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($calculatedItems as $key => $label)
                        <tr class="table-secondary">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $label }}</td>
                            <td>
                                <span class="form-control form-control-sm bg-light border-0 fw-bold">{{ number_format($medicalBill[$key] ?? 0, 2) }}</span>
                            </td>
                        </tr>
                    @endforeach
                    @foreach ($billItems as $key => $label)
                        <tr>
                            <td>{{ $loop->iteration + count($calculatedItems) }}</td>
                            <td>{{ $label }}</td>
                            <td>
                                <input type="number" step="0.01" class="form-control form-control-sm" wire:model.blur="medicalBill.{{ $key }}" placeholder="0.00">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="2" class="text-end">TOTAL ₦ </td>
                        <td>{{ number_format($billTotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-end fw-bold">Paid Bill ₦</td>
                        <td><input type="number" step="0.01" class="form-control form-control-sm" wire:model.live="billPaid" placeholder="0.00"></td>
                    </tr>
                    @if ($previousOutstanding > 0)
                        <tr class="text-muted small">
                            <td colspan="2" class="text-end fw-bold">Outstanding ₦</td>
                            <td>{{ number_format($previousOutstanding, 2) }}</td>
                        </tr>
                    @endif
                    <tr class="fw-bold">
                        <td colspan="2" class="text-end">Balance ₦</td>
                        <td>{{ number_format($billOutstanding, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
