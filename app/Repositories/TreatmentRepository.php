<?php

namespace App\Repositories;

use App\Models\TreatmentChart;

class TreatmentRepository extends BaseRepository
{
    public function __construct(TreatmentChart $model)
    {
        parent::__construct($model);
    }

    public function activeTreatments()
    {
        return $this->model
            ->where('is_completed', false)
            ->with('patient')
            ->latest()
            ->paginate(15);
    }

    public function forPatient(int $patientId)
    {
        return $this->model
            ->where('patient_id', $patientId)
            ->latest()
            ->paginate(15);
    }
}
