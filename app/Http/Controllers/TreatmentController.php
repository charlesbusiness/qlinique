<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTreatmentChartRequest;
use App\Models\TreatmentChart;
use App\Services\TreatmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  App\Livewire\TreatmentComponents\Treatment\WithConstants;

class TreatmentController extends Controller
{
    public function __construct(
        protected TreatmentService $treatmentService
    ) {}

    public function index(Request $request)
    {
        $treatments = TreatmentChart::with('patient.file')
            ->where('category', '!=', 'maternal_health')
            ->when($publishStatus = $request->input('publish_status'), function ($q) use ($publishStatus) {
                if ($publishStatus === 'draft') {
                    $q->where('is_draft', true)
                        ->where('created_by', Auth::id());
                }
            }, function ($q) {
                $q->where('is_draft', false);
            })
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_completed', false))
            ->when($pending = $request->input('pending'), function ($q) use ($pending) {
                $q->where('is_completed', false)
                    ->whereNotNull('treatment_schedule')
                    ->whereDoesntHave('complianceLogs', function ($cq) use ($pending) {
                        $cq->where('status', 'attended');
                        match ($pending) {
                            'today' => $cq->whereDate('date', today()),
                            'week' => $cq->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]),
                            'month' => $cq->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()]),
                        };
                    });
            })
            ->when($search = $request->input('search'), fn ($q) => $q->whereHas('patient', fn ($pq) => $pq
                ->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhereHas('file', fn ($f) => $f->where('file_number', 'like', "%{$search}%"))
            ))
            ->latest()
            ->paginate(10);

        return view('treatments.index', compact('treatments'));
    }

    public function maternal(Request $request)
    {
        $subCategory = $request->input('sub_category');

        $treatments = TreatmentChart::with('patient.file', 'maternalHealthRecord.antenatalVisits')
            ->where('category', 'maternal_health')
            ->when($subCategory, fn ($q) => $q->where('sub_category', $subCategory))
            ->when($publishStatus = $request->input('publish_status'), function ($q) use ($publishStatus) {
                if ($publishStatus === 'draft') {
                    $q->where('is_draft', true)
                        ->where('created_by', Auth::id());
                }
            }, function ($q) {
                $q->where('is_draft', false);
            })
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_completed', false))
            ->when($pending = $request->input('pending'), function ($q) use ($pending) {
                $q->where('is_completed', false)
                    ->whereNotNull('treatment_schedule')
                    ->whereDoesntHave('complianceLogs', function ($cq) use ($pending) {
                        $cq->where('status', 'attended');
                        match ($pending) {
                            'today' => $cq->whereDate('date', today()),
                            'week' => $cq->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]),
                            'month' => $cq->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()]),
                        };
                    });
            })
            ->when($search = $request->input('search'), fn ($q) => $q->whereHas('patient', fn ($pq) => $pq
                ->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhereHas('file', fn ($f) => $f->where('file_number', 'like', "%{$search}%"))
            ))
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        $maternalSubOptions = WithConstants::assessmentSubOptions('maternal_health');

        return view('treatments.maternal', compact('treatments', 'maternalSubOptions', 'subCategory'));
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
        $treatment->load('patient.file', 'vitals', 'medications', 'labTests', 'complianceLogs', 'physicalExaminations', 'rmeResults', 'treatmentPlanItems', 'maternalHealthRecord.antenatalVisits', 'invoices.items');

        return view('treatments.show', compact('treatment'));
    }

    public function edit(TreatmentChart $treatment)
    {
        if ($treatment->is_completed) {
            return redirect()->route('treatments.show', $treatment)
                ->with('error', 'Cannot edit a completed treatment.');
        }

        $treatment->load('patient.file', 'vitals', 'physicalExaminations', 'rmeResults', 'labTests', 'treatmentPlanItems');

        return view('treatments.edit', compact('treatment'));
    }

    public function update(Request $request, TreatmentChart $treatment)
    {
        if ($treatment->is_completed) {
            return redirect()->route('treatments.show', $treatment)
                ->with('error', 'Cannot edit a completed treatment.');
        }

        return redirect()->route('treatments.show', $treatment)
            ->with('status', 'Treatment chart updated successfully.');
    }

    public function compliance(TreatmentChart $treatment)
    {
        $treatment->load('patient.file');

        return view('treatments.compliance', compact('treatment'));
    }

    public function complete(TreatmentChart $treatment)
    {
        $this->treatmentService->complete($treatment);

        return redirect()->route('treatments.show', $treatment)
            ->with('status', 'Treatment marked as completed.');
    }

    public function createMaternal(Request $request)
    {
        $patientId = $request->input('patient_id');
        $subOption = $request->input('sub_option', 'antenatal_care');
        $startStep = $request->input('start_step', 1);
        $visitType = $request->input('visit_type', '');
        $visitId = $request->input('visit_id');

        return view('treatments.create-maternal', compact('patientId', 'subOption', 'startStep', 'visitType', 'visitId'));
    }

    public function editMaternal(TreatmentChart $treatment)
    {
        $record = $treatment->maternalHealthRecord()->firstOrFail();

        return view('treatments.edit-maternal', compact('treatment', 'record'));
    }
}
