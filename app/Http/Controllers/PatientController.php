<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\PatientService;
use App\Http\Requests\StorePatientRequest;
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

        $patients = Patient::with('familyFile')
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('file_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            }))
            ->when($accountType, fn($q) => $q->where('account_type', $accountType))
            ->latest()
            ->paginate(15);

        return view('patients.index', compact('patients'));
    }

    public function create(Request $request)
    {
        return view('patients.create', [
            'familyFileId' => $request->integer('family_file_id') ?: null,
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
        $patient->load('familyFile', 'treatmentCharts');
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
