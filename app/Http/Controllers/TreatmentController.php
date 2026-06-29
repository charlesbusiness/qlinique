<?php

namespace App\Http\Controllers;

use App\Models\TreatmentChart;
use App\Services\TreatmentService;
use App\Http\Requests\StoreTreatmentChartRequest;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function __construct(
        protected TreatmentService $treatmentService
    ) {}

    public function index(Request $request)
    {
        $treatments = TreatmentChart::with('patient')
            ->when($request->input('status') === 'active', fn($q) => $q->where('is_completed', false))
            ->latest()
            ->paginate(15);

        return view('treatments.index', compact('treatments'));
    }

    public function create(Request $request)
    {
        $patientId = $request->input('patient_id');
        return view('treatments.create', compact('patientId'));
    }

    public function store(StoreTreatmentChartRequest $request)
    {
        $this->treatmentService->create(
            $request->validated() + ['other_category' => $request->input('other_category')]
        );
        return redirect()->route('treatments.index')
            ->with('status', 'Treatment chart created successfully.');
    }

    public function show(TreatmentChart $treatment)
    {
        $treatment->load('patient', 'vitals', 'medications', 'labTests', 'complianceLogs');
        return view('treatments.show', compact('treatment'));
    }

    public function compliance(TreatmentChart $treatment)
    {
        $treatment->load('patient');
        return view('treatments.compliance', compact('treatment'));
    }
}
