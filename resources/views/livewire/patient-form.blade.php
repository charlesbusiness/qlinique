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
                (function () {
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
                            return { x: cx - rect.left, y: cy - rect.top };
                        }

                        var drawing = false;

                        function start(e) { e.preventDefault(); drawing = true; var p = getPos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); }
                        function move(e) { e.preventDefault(); if (!drawing) return; var p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); }
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
                        canvas.addEventListener('touchstart', start, { passive: false });
                        canvas.addEventListener('touchmove', move, { passive: false });
                        canvas.addEventListener('touchend', stop, { passive: false });

                        document.getElementById('clear-signature')?.addEventListener('click', function () {
                            if (!canvas || !ctx) return;
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            Livewire.find('{{ $__livewire->getId() }}').set('signature', '');
                        });

                        resize();
                        window.addEventListener('resize', resize);

                        @if ($existingSignatureType === 'drawn' && $existingSignature)
                            var img = new Image();
                            img.onload = function () {
                                if (canvas) { resize(); ctx.drawImage(img, 0, 0, canvas.width / (window.devicePixelRatio || 1), canvas.height / (window.devicePixelRatio || 1)); }
                            };
                            img.src = '{{ asset("storage/" . $existingSignature) }}';
                        @endif
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

                    document.querySelectorAll('#sig_typed, #sig_drawn, #sig_upload').forEach(function (el) {
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
            <button type="submit" class="btn btn-primary">
                {{ $patient ? 'Update Patient' : 'Register Patient' }}
            </button>
        </div>
    </form>
</div>
