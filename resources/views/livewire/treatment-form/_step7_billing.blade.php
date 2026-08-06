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
        <div class="table-responsive" x-data="{
                recalcBill() {
                    const itemKeys = ['registration', 'consultation', 'rapid_medical_examination', 'laboratory_test', 'admission', 'medical_service', 'logistics', 'maintenance', 'surgical_procedure'];
                    let total = 0;
                    for (const key of itemKeys) {
                        const el = this.$refs['amt_' + key];
                        const raw = el?.value ?? el?.textContent ?? '0';
                        total += parseFloat(String(raw).replace(/,/g, '')) || 0;
                    }
                    const paid = parseFloat(this.$refs.billPaid?.value) || 0;
                    const previous = parseFloat(String(this.$refs.billPreviousOutstanding?.textContent ?? '0').replace(/,/g, '')) || 0;
                    const fmt = (n) => n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    this.$refs.billTotal.textContent = fmt(total);
                    this.$refs.billBalance.textContent = fmt(previous + total - paid);
                }
            }" x-init="recalcBill()">
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
                                <span class="form-control form-control-sm bg-light border-0 fw-bold" x-ref="amt_{{ $key }}">{{ number_format($medicalBill[$key] ?? 0, 2) }}</span>
                            </td>
                        </tr>
                    @endforeach
                    @foreach ($billItems as $key => $label)
                        <tr>
                            <td>{{ $loop->iteration + count($calculatedItems) }}</td>
                            <td>{{ $label }}</td>
                            <td>
                                <input type="number" step="0.01" class="form-control form-control-sm" wire:model.blur="medicalBill.{{ $key }}" x-ref="amt_{{ $key }}" x-on:input="recalcBill()" placeholder="0.00">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="2" class="text-end">TOTAL ₦ </td>
                        <td x-ref="billTotal">{{ number_format($billTotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-end fw-bold">Paid Bill ₦</td>
                        <td><input type="number" step="0.01" class="form-control form-control-sm" wire:model.live="billPaid" x-ref="billPaid" x-on:input="recalcBill()" placeholder="0.00"></td>
                    </tr>
                    @if ($previousOutstanding > 0)
                        <tr class="text-muted small">
                            <td colspan="2" class="text-end fw-bold">Outstanding ₦</td>
                            <td x-ref="billPreviousOutstanding">{{ number_format($previousOutstanding, 2) }}</td>
                        </tr>
                    @endif
                    <tr class="fw-bold">
                        <td colspan="2" class="text-end">Balance ₦</td>
                        <td x-ref="billBalance">{{ number_format($billOutstanding, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
