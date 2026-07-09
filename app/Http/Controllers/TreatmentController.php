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
        $treatments = TreatmentChart::with('patient.file')
            ->when($request->input('status') === 'active', fn($q) => $q->where('is_completed', false))
            ->when($pending = $request->input('pending'), function($q) use ($pending) {
                $q->where('is_completed', false)
                  ->whereNotNull('treatment_schedule')
                  ->whereDoesntHave('complianceLogs', function($cq) use ($pending) {
                      $cq->where('status', 'attended');
                      match ($pending) {
                          'today' => $cq->whereDate('date', today()),
                          'week' => $cq->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]),
                          'month' => $cq->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()]),
                      };
                  });
            })
            ->when($search = $request->input('search'), fn($q) => $q->whereHas('patient', fn($pq) => $pq
                ->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhereHas('file', fn($f) => $f->where('file_number', 'like', "%{$search}%"))
            ))
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
            $request->validated() + [
                'other_category' => $request->input('other_category'),
                'sub_category' => $request->input('sub_category'),
            ]
        );
        return redirect()->route('treatments.index')
            ->with('status', 'Treatment chart created successfully.');
    }

    public function show(TreatmentChart $treatment)
    {
        $treatment->load('patient.file', 'vitals', 'medications', 'labTests', 'complianceLogs', 'physicalExaminations', 'rmeResults', 'treatmentPlanItems');
        return view('treatments.show', compact('treatment'));
    }

    public function edit(TreatmentChart $treatment)
    {
        $treatment->load('patient.file', 'vitals', 'physicalExaminations', 'rmeResults', 'labTests', 'treatmentPlanItems');
        return view('treatments.edit', compact('treatment'));
    }

    public function update(Request $request, TreatmentChart $treatment)
    {
        return redirect()->route('treatments.show', $treatment)
            ->with('status', 'Treatment chart updated successfully.');
    }

    public function compliance(TreatmentChart $treatment)
    {
        $treatment->load('patient.file');
        return view('treatments.compliance', compact('treatment'));
    }
}
