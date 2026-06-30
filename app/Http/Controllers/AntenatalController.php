<?php

namespace App\Http\Controllers;

use App\Models\AntenatalRecord;
use App\Models\Patient;
use App\Services\AntenatalService;
use Illuminate\Http\Request;

class AntenatalController extends Controller
{
    public function __construct(
        protected AntenatalService $antenatalService
    ) {}

    public function index()
    {
        $records = AntenatalRecord::with('patient.file')
            ->whereHas('patient', fn($q) => $q->where('is_active', true))
            ->latest()
            ->paginate(15);

        return view('antenatal.index', compact('records'));
    }

    public function create()
    {
        $patients = Patient::with('file')->where('is_active', true)->orderBy('name')->get();
        return view('antenatal.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'edd' => 'nullable|date',
            'gestation_weeks' => 'nullable|integer|min:1|max:45',
            'obstetric_history' => 'nullable|string',
            'risk_level' => 'nullable|in:low,medium,high',
        ]);

        $this->antenatalService->register($data);

        return redirect()->route('antenatal.index')
            ->with('status', 'Antenatal record created.');
    }

    public function show(AntenatalRecord $antenatal)
    {
        $antenatal->load('patient.file', 'partographs');
        return view('antenatal.show', compact('antenatal'));
    }

    public function partograph(AntenatalRecord $antenatal)
    {
        $antenatal->load('patient.file', 'partographs');
        return view('antenatal.partograph', compact('antenatal'));
    }

    public function storePartograph(Request $request, AntenatalRecord $antenatal)
    {
        $data = $request->validate([
            'cervical_dilation' => 'nullable|numeric|min:0|max:10',
            'fetal_heart_rate' => 'nullable|integer|min:0|max:250',
            'maternal_pulse' => 'nullable|integer|min:0|max:300',
            'blood_pressure_systolic' => 'nullable|numeric',
            'blood_pressure_diastolic' => 'nullable|numeric',
            'temperature' => 'nullable|numeric',
            'labour_progress' => 'nullable|string',
        ]);

        $data['recorded_at'] = now();
        $this->antenatalService->addPartograph($antenatal, $data);

        return redirect()->route('antenatal.partograph', $antenatal)
            ->with('status', 'Partograph entry added.');
    }
}
