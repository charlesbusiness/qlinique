<div>
    @include('livewire.treatment-form._header')

    <form wire:submit="nextStep">
        @if ($step === 0)
            @if ($selectedCategory === 'maternal_health')
                @if ($registrationSuccess)
                    @include('livewire.treatment-form._step0_antenatal_registration')
                @elseif ($maternalFlow === 'registration')
                    @include('livewire.treatment-form._step0_antenatal_registration')
                @elseif ($maternalFlow === 'antenatal_patient_selection')
                    @include('livewire.treatment-form._step0_antenatal_patient_selection')
                @elseif ($maternalFlow === 'revisit_patient_selection')
                    @include('livewire.treatment-form._step0_antenatal_patient_selection')
                @elseif ($maternalFlow === 'antenatal_options')
                    @include('livewire.treatment-form._step0_antenatal_options')
                @else
                    @include('livewire.treatment-form._step0_maternal_subcategories')
                @endif
            @else
                @include('livewire.treatment-form._step0_category_selection')
            @endif
        @elseif ($step === 1)
            @include('livewire.treatment-form._step1_history')
        @elseif ($step === 2)
            @include('livewire.treatment-form._step2_vitals')
        @elseif ($step === 3)
            @include('livewire.treatment-form._step3_physical_general')
        @elseif ($step === 4)
            @include('livewire.treatment-form._step4_physical_systemic')
        @elseif ($step === 5)
            @include('livewire.treatment-form._step4_investigation')
        @elseif ($step === 6)
            @include('livewire.treatment-form._step6_treatment_plan')
            @include('livewire.treatment-form._step6_consent')
        @elseif ($step === 7)
            @include('livewire.treatment-form._step7_billing')
        @endif

        @if ($step > 0)
            @include('livewire.treatment-form._navigation')
        @endif
    </form>

    @script
    <script>
        window.addEventListener('set-nok-signature', (e) => {
            $wire.set('nok_signature', e.detail.value);
        });

        window.addEventListener('set-reg-signature', (e) => {
            $wire.set('reg_signature', e.detail.value);
        });

        window.addEventListener('set-consent-pat-signature', (e) => {
            $wire.set('consent_drawn_patient', e.detail.value);
        });

        window.addEventListener('set-consent-wit-signature', (e) => {
            $wire.set('consent_drawn_witness', e.detail.value);
        });

        window.addEventListener('set-consent-doc-signature', (e) => {
            $wire.set('consent_drawn_physician', e.detail.value);
        });

        // All canvas configs: id → { wireProp, eventName }
        const canvasConfigs = {
            'nok-signature-canvas':             { wireProp: 'nok_signature',             eventName: 'set-nok-signature' },
            'reg-pat-sig-canvas':               { wireProp: 'reg_signature',             eventName: 'set-reg-signature' },
            'consent-pat-sig-canvas':           { wireProp: 'consent_drawn_patient',     eventName: 'set-consent-pat-signature' },
            'consent-wit-sig-canvas':           { wireProp: 'consent_drawn_witness',     eventName: 'set-consent-wit-signature' },
            'consent-doc-sig-canvas':           { wireProp: 'consent_drawn_physician',   eventName: 'set-consent-doc-signature' },
        };

        const canvasInstances = {};

        function initCanvas(id) {
            const config = canvasConfigs[id];
            if (!config) return;
            const canvas = document.getElementById(id);
            if (!canvas) return;
            // Clean up stale reference if canvas was removed and re-added
            if (canvasInstances[id] && !document.contains(canvasInstances[id].canvas)) {
                delete canvasInstances[id];
            }
            if (canvasInstances[id]) return;

            const ctx = canvas.getContext('2d');
            let drawing = false;

            function getPos(e) {
                const rect = canvas.getBoundingClientRect();
                if (e.touches) {
                    return {
                        x: (e.touches[0].clientX - rect.left) * (canvas.width / rect.width),
                        y: (e.touches[0].clientY - rect.top) * (canvas.height / rect.height),
                    };
                }
                return {
                    x: (e.clientX - rect.left) * (canvas.width / rect.width),
                    y: (e.clientY - rect.top) * (canvas.height / rect.height),
                };
            }

            canvas.addEventListener('mousedown', (e) => { drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); });
            canvas.addEventListener('mousemove', (e) => { if (!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); });
            canvas.addEventListener('mouseup', () => { drawing = false; window.dispatchEvent(new CustomEvent(config.eventName, { detail: { value: canvas.toDataURL('image/png') } })); });
            canvas.addEventListener('mouseleave', () => { if (drawing) { drawing = false; window.dispatchEvent(new CustomEvent(config.eventName, { detail: { value: canvas.toDataURL('image/png') } })); } });
            canvas.addEventListener('touchstart', (e) => { e.preventDefault(); drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); }, { passive: false });
            canvas.addEventListener('touchmove', (e) => { e.preventDefault(); if (!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); }, { passive: false });
            canvas.addEventListener('touchend', (e) => { e.preventDefault(); drawing = false; window.dispatchEvent(new CustomEvent(config.eventName, { detail: { value: canvas.toDataURL('image/png') } })); }, { passive: false });

            ctx.strokeStyle = '#000';
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';

            canvasInstances[id] = { canvas, ctx };
        }

        function clearCanvas(id) {
            const inst = canvasInstances[id];
            if (!inst) return;
            const config = canvasConfigs[id];
            inst.ctx.clearRect(0, 0, inst.canvas.width, inst.canvas.height);
            window.dispatchEvent(new CustomEvent(config.eventName, { detail: { value: '' } }));
            delete canvasInstances[id];
        }

        // Clear buttons
        window.addEventListener('clear-nok-canvas', () => clearCanvas('nok-signature-canvas'));
        window.addEventListener('clear-reg-pat-canvas', () => clearCanvas('reg-pat-sig-canvas'));
        window.addEventListener('clear-consent-pat-canvas', () => clearCanvas('consent-pat-sig-canvas'));
        window.addEventListener('clear-consent-wit-canvas', () => clearCanvas('consent-wit-sig-canvas'));
        window.addEventListener('clear-consent-doc-canvas', () => clearCanvas('consent-doc-sig-canvas'));

        // Watch for canvases being added to the DOM
        const observer = new MutationObserver((mutations) => {
            for (const mutation of mutations) {
                for (const node of mutation.addedNodes) {
                    if (node.nodeType !== 1) continue;
                    if (node.id && canvasConfigs[node.id]) {
                        setTimeout(() => initCanvas(node.id), 50);
                    }
                    if (node.querySelectorAll) {
                        node.querySelectorAll('canvas').forEach(canvas => {
                            if (canvas.id && canvasConfigs[canvas.id]) {
                                setTimeout(() => initCanvas(canvas.id), 50);
                            }
                        });
                    }
                }
            }
        });
        observer.observe(document.body, { childList: true, subtree: true });
    </script>
    @endscript
</div>
