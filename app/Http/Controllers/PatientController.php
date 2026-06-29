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
        $query = $request->get('search');

        $patients = $query
            ? $this->patientService->search($query)
            : Patient::with('accountHolder')->latest()->paginate(15);

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(StorePatientRequest $request)
    {
        $this->patientService->register(
            $request->validated(),
            $request->file('photo')
        );

        return redirect()->route('patients.index')
            ->with('status', 'Patient registered successfully.');
    }

    public function show(Patient $patient)
    {
        $patient->load('accountHolder', 'dependants', 'treatmentCharts');
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
            $request->file('photo')
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
