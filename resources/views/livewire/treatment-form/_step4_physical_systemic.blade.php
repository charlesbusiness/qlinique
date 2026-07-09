{{-- (4) Abdominal --}}
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">(4) ABDOMINAL EXAMINATION</h5>
        <div class="row">
            @foreach (['tenderness' => 'Tenderness', 'abnormal_masses' => 'Abnormal Masses', 'pain' => 'Pain', 'tympanites_bloating' => 'Tympanites/Bloating', 'ascites_fluid' => 'Ascites/Fluid Retain'] as $val => $label)
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" wire:model="abdominalFindings" value="{{ $val }}" id="abd_{{ $val }}">
                        <label class="form-check-label" for="abd_{{ $val }}">{{ $label }}</label>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-2">
            <label class="form-label">Comment</label>
            <textarea class="form-control" wire:model="abdominalComment" rows="2"></textarea>
        </div>
    </div>
</div>

{{-- (5) Reflex / Nerves --}}
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">(5) REFLEX / NERVES FUNCTION</h5>
        <div class="mb-2">
            @foreach (['satisfied' => 'Satisfied', 'fear' => 'Fear', 'poor_unresponsive' => 'Poor/Unresponsive'] as $val => $label)
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" wire:model="reflexFinding" value="{{ $val }}" id="reflex_{{ $val }}">
                    <label class="form-check-label" for="reflex_{{ $val }}">{{ $label }}</label>
                </div>
            @endforeach
        </div>
        <div class="mt-2">
            <label class="form-label">Comment</label>
            <textarea class="form-control" wire:model="reflexComment" rows="2"></textarea>
        </div>
    </div>
</div>

{{-- (6) Hair Condition --}}
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">(6) HAIR CONDITION</h5>
        <div class="row">
            @foreach (['normal' => 'Normal', 'pallor' => 'Pallor', 'dry' => 'Dry', 'cool' => 'Cool', 'rough' => 'Rough', 'tattered' => 'Tattered', 'shining' => 'Shining', 'scantly' => 'Scantly'] as $val => $label)
                <div class="col-md-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" wire:model="hairFindings" value="{{ $val }}" id="hair_{{ $val }}">
                        <label class="form-check-label" for="hair_{{ $val }}">{{ $label }}</label>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-2">
            <label class="form-label">Comment</label>
            <textarea class="form-control" wire:model="hairComment" rows="2"></textarea>
        </div>
    </div>
</div>

{{-- (7) Skin Scanning --}}
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">(7) SKIN SCANNING</h5>
        <div class="row">
            @foreach (['perfect' => 'Perfect', 'unusual_moles' => 'Unusual Moles', 'rashes' => 'Rashes', 'rough' => 'Rough', 'dry' => 'Dry'] as $val => $label)
                <div class="col-md-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" wire:model="skinFindings" value="{{ $val }}" id="skin_{{ $val }}">
                        <label class="form-check-label" for="skin_{{ $val }}">{{ $label }}</label>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-2">
            <label class="form-label">Comment</label>
            <textarea class="form-control" wire:model="skinComment" rows="2"></textarea>
        </div>
    </div>
</div>
