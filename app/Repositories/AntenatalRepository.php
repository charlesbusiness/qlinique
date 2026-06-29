<?php

namespace App\Repositories;

use App\Models\AntenatalRecord;

class AntenatalRepository extends BaseRepository
{
    public function __construct(AntenatalRecord $model)
    {
        parent::__construct($model);
    }

    public function forPatient(int $patientId)
    {
        return $this->model
            ->where('patient_id', $patientId)
            ->with('partographs')
            ->latest()
            ->get();
    }

    public function activePregnancies()
    {
        return $this->model
            ->where('edd', '>=', now())
            ->with('patient')
            ->latest()
            ->paginate(15);
    }
}
