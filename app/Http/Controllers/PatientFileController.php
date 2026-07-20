<?php

namespace App\Http\Controllers;

use App\Models\PatientFile;

class PatientFileController extends Controller
{
    public function members(PatientFile $patientFile)
    {
        $patientFile->load('patients.file');

        return view('patient-files.members', compact('patientFile'));
    }
}
