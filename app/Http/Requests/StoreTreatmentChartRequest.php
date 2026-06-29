<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTreatmentChartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'category' => 'required|in:checkup,treatment,emergency',
            'visit_date' => 'required|date',
            'presenting_complaint' => 'nullable|string',
            'symptoms' => 'nullable|string',
            'clinical_notes' => 'nullable|string',
            'primary_diagnosis' => 'nullable|string',
            'secondary_diagnosis' => 'nullable|string',
            'diagnosis_notes' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'treatment_schedule' => 'nullable|string',
        ];
    }
}
