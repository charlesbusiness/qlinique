{{-- RME --}}
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">RAPID MEDICAL EXAMINATION (RME)</h5>
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <select class="form-select form-select-sm" wire:model.live="rmeNewTest">
                    <option value="">Select test...</option>
                    @foreach (\App\Livewire\TreatmentForm::rmeTestOptions() as $val)
                        <option value="{{ $val }}">{{ $val }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-primary btn-sm" wire:click="addRmeTest">+ Add</button>
            </div>
        </div>
        @if (count($rmeResults))
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Test</th>
                            <th>Result</th>
                            <th>Amount (₦)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rmeResults as $i => $rme)
                            <tr>
                                <td class="align-middle fw-medium">{{ $rme['test_name'] }}</td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" wire:model="rmeResults.{{ $i }}.result" placeholder="Result / (+/-)">
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="form-control form-control-sm" wire:model.blur="rmeResults.{{ $i }}.amount" placeholder="0.00">
                                </td>
                                <td class="align-middle">
                                    <button type="button" class="btn btn-outline-danger btn-sm" wire:click="removeRmeTest({{ $i }})">&times;</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        <div class="mb-0">
            <label class="form-label">Comment</label>
            <textarea class="form-control" wire:model="rmeComment" rows="2"></textarea>
        </div>
    </div>
</div>

{{-- Further Investigation --}}
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">FURTHER INVESTIGATION (Lab Test Required)</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name of Test</th>
                        <th>Type of Sample/Specimen</th>
                        <th>Amount (₦)</th>
                        <th>Upload Result</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($labTests as $i => $lab)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td>
                                <input type="text" class="form-control form-control-sm" wire:model="labTests.{{ $i }}.test_type" placeholder="Test name...">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" wire:model="labTests.{{ $i }}.sample_type" placeholder="Blood, Urine, etc.">
                            </td>
                            <td>
                                <input type="number" step="0.01" class="form-control form-control-sm" wire:model.blur="labTests.{{ $i }}.amount" placeholder="0.00">
                            </td>
                            <td>
                                <input type="file" class="form-control form-control-sm" wire:model="labTestUploads.{{ $i }}" accept="image/*,.pdf">
                            </td>
                            <td class="align-middle">
                                <button type="button" class="btn btn-outline-danger btn-sm" wire:click="removeLabTest({{ $i }})">&times;</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button type="button" class="btn btn-outline-primary btn-sm mb-3" wire:click="addLabTest">+ Add Lab Test</button>
    </div>
</div>

{{-- Clinical Judgement --}}
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">CLINICAL JUDGEMENT (Diagnosis)</h5>
        <textarea class="form-control" wire:model="primary_diagnosis" rows="4" placeholder="Enter diagnosis..."></textarea>
    </div>
</div>
