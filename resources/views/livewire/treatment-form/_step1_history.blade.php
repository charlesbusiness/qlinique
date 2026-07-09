<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">Case History</h5>
        <div class="mb-3">
            <label class="form-label">Finding on History (Case History)</label>
            <textarea class="form-control @error('finding_on_history') is-invalid @enderror" wire:model="finding_on_history" rows="3" placeholder="Enter case history findings..."></textarea>
            @error('finding_on_history') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Previous Treatment History</label>
            <textarea class="form-control @error('previous_treatment_history') is-invalid @enderror" wire:model="previous_treatment_history" rows="3" placeholder="Enter previous treatment history..."></textarea>
            @error('previous_treatment_history') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Recommended Drugs / Routine Medication</label>
            <textarea class="form-control @error('recommended_drugs') is-invalid @enderror" wire:model="recommended_drugs" rows="3" placeholder="Names of medication..."></textarea>
            @error('recommended_drugs') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Allergies / Adverse Effect</label>
            <textarea class="form-control @error('allergies') is-invalid @enderror" wire:model="allergies" rows="2" placeholder="Food, drugs, environment, weathers..."></textarea>
            @error('allergies') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
