<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Models\Patient;
use App\Services\PatientService;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __construct(
        protected PatientService $patientService
    ) {}

    public function index(Request $request)
    {
        $search = $request->get('search');
        $accountType = $request->get('account_type');
        $tab = $request->get('tab', 'normal');

        $patients = Patient::with('file')
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('file', fn ($f) => $f->where('file_number', 'like', "%{$search}%"));
            }))
            ->when($accountType, fn ($q) => $q->whereHas('file', fn ($f) => $f->where('type', $accountType)))
            ->when($tab === 'maternal', fn ($q) => $q->where('patient_type', 'antenatal'))
            ->when($tab === 'normal', fn ($q) => $q->where('patient_type', '!=', 'antenatal'))
            ->latest()
            ->paginate(15)
            ->appends(['tab' => $tab, 'search' => $search, 'account_type' => $accountType]);

        $normalCount = Patient::where('patient_type', '!=', 'antenatal')->count();
        $maternalCount = Patient::where('patient_type', 'antenatal')->count();

        return view('patients.index', compact('patients', 'tab', 'normalCount', 'maternalCount'));
    }

    public function create(Request $request)
    {
        return view('patients.create', [
            'fileId' => $request->integer('file_id') ?: null,
        ]);
    }

    public function store(StorePatientRequest $request)
    {
        $this->patientService->register(
            $request->validated(),
            $request->file('photo'),
            $request->file('signature_upload')
        );

        return redirect()->route('patients.index')
            ->with('status', 'Patient registered successfully.');
    }

    public function show(Patient $patient)
    {
        $patient->load('file', 'treatmentCharts');

        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(StorePatientRequest $request, Patient $patient)
    {
        $this->patientService->update(
            $patient,
            $request->validated(),
            $request->file('photo'),
            $request->file('signature_upload')
        );

        return redirect()->route('patients.index')
            ->with('status', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('status', 'Patient deleted successfully.');
    }
}
