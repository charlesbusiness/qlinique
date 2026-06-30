<div>
    @if ($patient)
    {{-- Edit mode: single-page form --}}
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
                <label class="form-label">Age</label>
                <input type="number" step="1" class="form-control @error('age') is-invalid @enderror" wire:model="age">
                @error('age') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

            <div class="col-md-6 mb-3">
                <label class="form-label">Patient Type</label>
                <select class="form-select @error('patient_type') is-invalid @enderror" wire:model="patient_type">
                    <option value="">Select...</option>
                    @foreach (\App\Models\Patient::patientTypeOptions() as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('patient_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        @if (in_array($account_type, ['family', 'corporate']))
        <hr>
        <h5 class="mb-3">{{ ucfirst($account_type) }} File</h5>

        @if (session('family_status'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('family_status') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        @if (!$show_create_family)
        <div class="mb-3">
            <label class="form-label">Select Existing {{ ucfirst($account_type) }} File</label>
            <select class="form-select" wire:model="selected_family_id">
                <option value="">— None —</option>
                @foreach ($familyFiles as $file)
                <option value="{{ $file->id }}">{{ $file->file_number }} — {{ $file->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="button" class="btn btn-outline-primary btn-sm" wire:click="toggleCreateFamily">
            + Create New {{ ucfirst($account_type) }} File
        </button>
        @else
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ ucfirst($account_type) }} Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('new_family_name') is-invalid @enderror" wire:model="new_family_name" placeholder="e.g. Smith Family">
                @error('new_family_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('new_family_email') is-invalid @enderror" wire:model="new_family_email">
                @error('new_family_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('new_family_phone') is-invalid @enderror" wire:model="new_family_phone">
                @error('new_family_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" wire:model="new_family_address" rows="2"></textarea>
            </div>
        </div>

        <div class="d-flex gap-2 mb-3">
            <button type="button" class="btn btn-success" wire:click="createFamilyFile">Create & Use</button>
            <button type="button" class="btn btn-outline-secondary" wire:click="toggleCreateFamily">Cancel</button>
        </div>
        @endif
        @endif

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
                <img src="{{ asset('storage/' . $existingPhoto) }}" class="rounded" style="max-height: 80px;">
            </div>
            @endif
            <input type="file" class="form-control @error('photo') is-invalid @enderror" wire:model="photo" accept="image/*">
            @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @if ($photo)
            <div class="mt-2">
                <img src="{{ $photo->temporaryUrl() }}" class="rounded border" style="max-height: 80px;">
            </div>
            @endif
        </div>

        <hr>
        <h5 class="mb-3">Signature</h5>

        <div class="mb-3">
            <div class="btn-group" role="group">
                <input type="radio" class="btn-check" wire:model.live="signature_type" value="typed" id="sig_typed" autocomplete="off">
                <label class="btn btn-outline-primary" for="sig_typed">Type</label>

                <input type="radio" class="btn-check" wire:model.live="signature_type" value="drawn" id="sig_drawn" autocomplete="off">
                <label class="btn btn-outline-primary" for="sig_drawn">Draw</label>

                <input type="radio" class="btn-check" wire:model.live="signature_type" value="uploaded" id="sig_upload" autocomplete="off">
                <label class="btn btn-outline-primary" for="sig_upload">Upload</label>
            </div>
        </div>

        @if ($signature_type === 'typed')
        <div class="mb-3">
            <label class="form-label">Type Signature</label>
            <input type="text" class="form-control @error('signature') is-invalid @enderror" wire:model="signature" placeholder="Enter full name as signature">
            @error('signature') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @if ($signature)
            <div class="mt-2 p-3 border rounded bg-light" style="font-family: 'Dancing Script', 'Pacifico', cursive; font-size: 2rem;">
                {{ $signature }}
            </div>
            @endif
        </div>
        @elseif ($signature_type === 'uploaded')
        <div class="mb-3">
            <label class="form-label">Upload Signature</label>
            <input type="file" class="form-control @error('signature_upload') is-invalid @enderror" wire:model="signature_upload" accept="image/*">
            @error('signature_upload') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @if ($signature_upload)
            <div class="mt-2">
                <img src="{{ $signature_upload->temporaryUrl() }}" class="border rounded" style="max-height: 80px;">
            </div>
            @endif
        </div>
        @endif

        <div wire:ignore>
            <div class="mb-3 d-none" id="draw-signature-wrap">
                <label class="form-label">Draw Signature</label>
                <div class="border rounded p-1" style="background: #fff;">
                    <canvas id="signature-canvas"
                        class="w-100 rounded"
                        style="height: 200px; touch-action: none; cursor: crosshair;"></canvas>
                </div>
                <div class="mt-2 d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-danger" id="clear-signature">Clear</button>
                </div>
            </div>

            <script>
                (function() {
                    var inited = false;
                    var canvas, ctx;

                    function initCanvas() {
                        canvas = document.getElementById('signature-canvas');
                        if (!canvas) return;
                        ctx = canvas.getContext('2d');

                        function resize() {
                            var rect = canvas.getBoundingClientRect();
                            if (rect.width === 0 || rect.height === 0) return;
                            canvas.width = rect.width * (window.devicePixelRatio || 1);
                            canvas.height = rect.height * (window.devicePixelRatio || 1);
                            ctx.scale(window.devicePixelRatio || 1, window.devicePixelRatio || 1);
                            ctx.strokeStyle = '#000';
                            ctx.lineWidth = 2;
                            ctx.lineCap = 'round';
                            ctx.lineJoin = 'round';
                        }

                        function getPos(e) {
                            var rect = canvas.getBoundingClientRect();
                            var cx = e.touches ? e.touches[0].clientX : e.clientX;
                            var cy = e.touches ? e.touches[0].clientY : e.clientY;
                            return {
                                x: cx - rect.left,
                                y: cy - rect.top
                            };
                        }

                        var drawing = false;

                        function start(e) {
                            e.preventDefault();
                            drawing = true;
                            var p = getPos(e);
                            ctx.beginPath();
                            ctx.moveTo(p.x, p.y);
                        }

                        function move(e) {
                            e.preventDefault();
                            if (!drawing) return;
                            var p = getPos(e);
                            ctx.lineTo(p.x, p.y);
                            ctx.stroke();
                        }

                        function stop(e) {
                            e.preventDefault();
                            if (!drawing) return;
                            drawing = false;
                            ctx.closePath();
                            Livewire.find('{{ $__livewire->getId() }}').set('signature', canvas.toDataURL('image/png'));
                        }

                        canvas.addEventListener('mousedown', start);
                        canvas.addEventListener('mousemove', move);
                        canvas.addEventListener('mouseup', stop);
                        canvas.addEventListener('mouseleave', stop);
                        canvas.addEventListener('touchstart', start, {
                            passive: false
                        });
                        canvas.addEventListener('touchmove', move, {
                            passive: false
                        });
                        canvas.addEventListener('touchend', stop, {
                            passive: false
                        });

                        document.getElementById('clear-signature')?.addEventListener('click', function() {
                            if (!canvas || !ctx) return;
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            Livewire.find('{{ $__livewire->getId() }}').set('signature', '');
                        });

                        resize();
                        window.addEventListener('resize', resize);

                        if ($existingSignatureType === 'drawn' && $existingSignature) {
                            var img = new Image();
                            img.onload = function() {
                                if (canvas) {
                                    resize();
                                    ctx.drawImage(img, 0, 0, canvas.width / (window.devicePixelRatio || 1), canvas.height / (window.devicePixelRatio || 1));
                                }
                            };
                            img.src = '{{ asset("storage/" . $existingSignature) }}';

                        }

                    }

                    function toggleDraw() {
                        var wrap = document.getElementById('draw-signature-wrap');
                        var drawn = document.getElementById('sig_drawn');
                        if (!wrap || !drawn) return;
                        if (drawn.checked) {
                            wrap.classList.remove('d-none');
                            if (!inited) {
                                inited = true;
                                setTimeout(initCanvas, 50);
                            }
                        } else {
                            wrap.classList.add('d-none');
                        }
                    }

                    document.querySelectorAll('#sig_typed, #sig_drawn, #sig_upload').forEach(function(el) {
                        el.addEventListener('change', toggleDraw);
                    });

                    if (document.getElementById('sig_drawn')?.checked) {
                        inited = true;
                        setTimeout(initCanvas, 50);
                    }
                })();
            </script>
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
            <button type="submit" class="btn btn-primary">Update Patient</button>
        </div>
    </form>
    @else
    {{-- Create mode: multi-step form --}}
    <form wire:submit="save">
        <div class="mb-4">
            <div class="d-flex gap-2 flex-wrap">
                @foreach ([1 => 'Account Type', 2 => 'Personal Info', 3 => 'Additional Info', 4 => 'Next of Kin & Consent', 5 => 'Summary'] as $num => $label)
                <span class="badge {{ $step >= $num ? 'bg-primary' : 'bg-secondary' }} fs-6 px-3 py-2 {{ $step === $num ? '' : 'd-none d-md-inline' }}">{{ $num }}. {{ $label }}</span>
                @endforeach
            </div>
        </div>

        {{-- Step 1: Account Type --}}
        @if ($step === 1)
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Account Type</label>
                <select class="form-select @error('account_type') is-invalid @enderror" wire:model.live="account_type">
                    <option value="individual">Individual</option>
                    <option value="family">Family</option>
                    <option value="corporate">Corporate</option>
                </select>
                @error('account_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        @if (in_array($account_type, ['family', 'corporate']))
        <hr>
        <h5 class="mb-3">{{ ucfirst($account_type) }} File</h5>

        @if (session('family_status'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('family_status') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        @if (!$show_create_family)
        <div class="mb-3">
            <label class="form-label">Select Existing {{ ucfirst($account_type) }} File</label>
            <select class="form-select" wire:model="selected_family_id">
                <option value="">— Select —</option>
                @foreach ($familyFiles as $file)
                <option value="{{ $file->id }}">{{ $file->file_number }} — {{ $file->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="button" class="btn btn-outline-primary" wire:click="toggleCreateFamily">
            + Create New {{ ucfirst($account_type) }} File
        </button>
        @else
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ ucfirst($account_type) }} Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('new_family_name') is-invalid @enderror" wire:model="new_family_name" placeholder="e.g. Smith Family">
                @error('new_family_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('new_family_email') is-invalid @enderror" wire:model="new_family_email">
                @error('new_family_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('new_family_phone') is-invalid @enderror" wire:model="new_family_phone">
                @error('new_family_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" wire:model="new_family_address" rows="2"></textarea>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success" wire:click="createFamilyFile">Create & Continue</button>
            <button type="button" class="btn btn-outline-secondary" wire:click="toggleCreateFamily">Cancel</button>
        </div>
        @endif
        @endif
        @endif

        {{-- Step 2: Personal Info --}}
        @if ($step === 2)
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
                <label class="form-label">Age</label>
                <input type="number" step="1" class="form-control @error('age') is-invalid @enderror" wire:model="age">
                @error('age') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                <label class="form-label">Patient Type</label>
                <select class="form-select @error('patient_type') is-invalid @enderror" wire:model="patient_type">
                    <option value="">Select...</option>
                    @foreach (\App\Models\Patient::patientTypeOptions() as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('patient_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea class="form-control" wire:model="address" rows="2"></textarea>
        </div>
        @endif

        {{-- Step 3: Additional Info --}}
        @if ($step === 3)
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
            <input type="file" class="form-control @error('photo') is-invalid @enderror" wire:model="photo" accept="image/*">
            @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @if ($photo)
            <div class="mt-2">
                <img src="{{ $photo->temporaryUrl() }}" class="rounded border" style="max-height: 80px;">
            </div>
            @endif
        </div>

        <hr>
        <h5 class="mb-3">Signature</h5>

        <div class="mb-3">
            <div class="btn-group" role="group">
                <input type="radio" class="btn-check" wire:model.live="signature_type" value="typed" id="sig_typed" autocomplete="off">
                <label class="btn btn-outline-primary" for="sig_typed">Type</label>

                <input type="radio" class="btn-check" wire:model.live="signature_type" value="drawn" id="sig_drawn" autocomplete="off">
                <label class="btn btn-outline-primary" for="sig_drawn">Draw</label>

                <input type="radio" class="btn-check" wire:model.live="signature_type" value="uploaded" id="sig_upload" autocomplete="off">
                <label class="btn btn-outline-primary" for="sig_upload">Upload</label>
            </div>
        </div>

        @if ($signature_type === 'typed')
        <div class="mb-3">
            <label class="form-label">Type Signature</label>
            <input type="text" class="form-control @error('signature') is-invalid @enderror" wire:model="signature" placeholder="Enter full name as signature">
            @error('signature') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @if ($signature)
            <div class="mt-2 p-3 border rounded bg-light" style="font-family: 'Dancing Script', 'Pacifico', cursive; font-size: 2rem;">
                {{ $signature }}
            </div>
            @endif
        </div>
        @elseif ($signature_type === 'uploaded')
        <div class="mb-3">
            <label class="form-label">Upload Signature</label>
            <input type="file" class="form-control @error('signature_upload') is-invalid @enderror" wire:model="signature_upload" accept="image/*">
            @error('signature_upload') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @if ($signature_upload)
            <div class="mt-2">
                <img src="{{ $signature_upload->temporaryUrl() }}" class="border rounded" style="max-height: 80px;">
            </div>
            @endif
        </div>
        @endif

        <div wire:ignore>
            <div class="mb-3 d-none" id="draw-signature-wrap">
                <label class="form-label">Draw Signature</label>
                <div class="border rounded p-1" style="background: #fff;">
                    <canvas id="signature-canvas"
                        class="w-100 rounded"
                        style="height: 200px; touch-action: none; cursor: crosshair;"></canvas>
                </div>
                <div class="mt-2 d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-danger" id="clear-signature">Clear</button>
                </div>
            </div>

            <script>
                (function() {
                    var inited = false;
                    var canvas, ctx;

                    function initCanvas() {
                        canvas = document.getElementById('signature-canvas');
                        if (!canvas) return;
                        ctx = canvas.getContext('2d');

                        function resize() {
                            var rect = canvas.getBoundingClientRect();
                            if (rect.width === 0 || rect.height === 0) return;
                            canvas.width = rect.width * (window.devicePixelRatio || 1);
                            canvas.height = rect.height * (window.devicePixelRatio || 1);
                            ctx.scale(window.devicePixelRatio || 1, window.devicePixelRatio || 1);
                            ctx.strokeStyle = '#000';
                            ctx.lineWidth = 2;
                            ctx.lineCap = 'round';
                            ctx.lineJoin = 'round';
                        }

                        function getPos(e) {
                            var rect = canvas.getBoundingClientRect();
                            var cx = e.touches ? e.touches[0].clientX : e.clientX;
                            var cy = e.touches ? e.touches[0].clientY : e.clientY;
                            return {
                                x: cx - rect.left,
                                y: cy - rect.top
                            };
                        }

                        var drawing = false;

                        function start(e) {
                            e.preventDefault();
                            drawing = true;
                            var p = getPos(e);
                            ctx.beginPath();
                            ctx.moveTo(p.x, p.y);
                        }

                        function move(e) {
                            e.preventDefault();
                            if (!drawing) return;
                            var p = getPos(e);
                            ctx.lineTo(p.x, p.y);
                            ctx.stroke();
                        }

                        function stop(e) {
                            e.preventDefault();
                            if (!drawing) return;
                            drawing = false;
                            ctx.closePath();
                            Livewire.find('{{ $__livewire->getId() }}').set('signature', canvas.toDataURL('image/png'));
                        }

                        canvas.addEventListener('mousedown', start);
                        canvas.addEventListener('mousemove', move);
                        canvas.addEventListener('mouseup', stop);
                        canvas.addEventListener('mouseleave', stop);
                        canvas.addEventListener('touchstart', start, {
                            passive: false
                        });
                        canvas.addEventListener('touchmove', move, {
                            passive: false
                        });
                        canvas.addEventListener('touchend', stop, {
                            passive: false
                        });

                        document.getElementById('clear-signature')?.addEventListener('click', function() {
                            if (!canvas || !ctx) return;
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            Livewire.find('{{ $__livewire->getId() }}').set('signature', '');
                        });

                        resize();
                        window.addEventListener('resize', resize);
                    }

                    function toggleDraw() {
                        var wrap = document.getElementById('draw-signature-wrap');
                        var drawn = document.getElementById('sig_drawn');
                        if (!wrap || !drawn) return;
                        if (drawn.checked) {
                            wrap.classList.remove('d-none');
                            if (!inited) {
                                inited = true;
                                setTimeout(initCanvas, 50);
                            }
                        } else {
                            wrap.classList.add('d-none');
                        }
                    }

                    document.querySelectorAll('#sig_typed, #sig_drawn, #sig_upload').forEach(function(el) {
                        el.addEventListener('change', toggleDraw);
                    });

                    if (document.getElementById('sig_drawn')?.checked) {
                        inited = true;
                        setTimeout(initCanvas, 50);
                    }
                })();
            </script>
        </div>
        @endif

        {{-- Step 4: Next of Kin & Consent --}}
        @if ($step === 4)
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
        @endif

        {{-- Step 5: Summary & Save --}}
        @if ($step === 5)
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="card mb-3">
            <div class="card-body">
                <h6>Account Info</h6>
                <p class="mb-1"><strong>Account Type:</strong> {{ ucfirst($account_type) }}</p>
                @if (in_array($account_type, ['family', 'corporate']) && $selected_family_id)
                @php
                $family = \App\Models\FamilyFile::find($selected_family_id);
                @endphp
                @if ($family)
                <p class="mb-1"><strong>{{ ucfirst($account_type) }} File:</strong> {{ $family->file_number }} — {{ $family->name }}</p>
                @endif
                @endif

                <h6 class="mt-3">Personal Info</h6>
                <p class="mb-1"><strong>Name:</strong> {{ $name }}</p>
                <p class="mb-1"><strong>Gender:</strong> {{ ucfirst($gender) }}</p>
                <p class="mb-1"><strong>Age:</strong> {{ $age }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $phone ?: '—' }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $email ?: '—' }}</p>
                <p class="mb-1"><strong>Occupation:</strong> {{ $occupation ?: '—' }}</p>
                <p class="mb-1"><strong>Marital Status:</strong> {{ $marital_status ?: '—' }}</p>
                <p class="mb-1"><strong>Patient Type:</strong> {{ $patient_type ? \App\Models\Patient::patientTypeOptions()[$patient_type] ?? $patient_type : '—' }}</p>

                @if ($address)
                <p class="mb-1"><strong>Address:</strong> {{ $address }}</p>
                @endif

                @if ($religion || $denomination)
                <h6 class="mt-3">Religion</h6>
                <p class="mb-1"><strong>Religion:</strong> {{ $religion ?: '—' }}</p>
                <p class="mb-1"><strong>Denomination:</strong> {{ $denomination ?: '—' }}</p>
                @endif

                @if ($signature_type)
                <h6 class="mt-3">Signature</h6>
                <p class="mb-1"><strong>Type:</strong> {{ ucfirst($signature_type) }}</p>
                @endif

                @if ($next_of_kin['name'] ?? null)
                <h6 class="mt-3">Next of Kin</h6>
                <p class="mb-1"><strong>Name:</strong> {{ $next_of_kin['name'] }}</p>
                <p class="mb-1"><strong>Relationship:</strong> {{ $next_of_kin['relationship'] ?? '—' }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $next_of_kin['phone'] ?? '—' }}</p>
                @if ($next_of_kin['address'] ?? null)
                <p class="mb-1"><strong>Address:</strong> {{ $next_of_kin['address'] }}</p>
                @endif
                @endif

                <h6 class="mt-3">Consent</h6>
                <p class="mb-1">Treatment: {{ !empty($consent['treatment']) ? '✅' : '❌' }}</p>
                <p class="mb-1">Data Privacy: {{ !empty($consent['privacy']) ? '✅' : '❌' }}</p>
            </div>
        </div>
        @endif

        <div class="d-flex justify-content-between">
            @if ($step > 1)
            <button type="button" class="btn btn-outline-secondary" wire:click="prevStep">Previous</button>
            @else
            <div></div>
            @endif

            @if ($step < 5)
                <button type="button" class="btn btn-primary" wire:click="nextStep">Next</button>
                @else
                <button type="submit" class="btn btn-success">Register Patient</button>
                @endif
        </div>
    </form>
    @endif
</div>