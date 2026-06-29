<?php

namespace App\Repositories;

use App\Models\Patient;

class PatientRepository extends BaseRepository
{
    public function __construct(Patient $model)
    {
        parent::__construct($model);
    }

    public function findByFileNumber(string $fileNumber): ?Patient
    {
        return $this->model->where('file_number', $fileNumber)->first();
    }

    public function search(string $query)
    {
        return $this->model
            ->where('name', 'like', "%{$query}%")
            ->orWhere('file_number', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->latest()
            ->paginate(15);
    }

    public function familyMembers(int $familyFileId)
    {
        return $this->model
            ->where('family_file_id', $familyFileId)
            ->orWhereHas('familyFile', fn($q) => $q->where('id', $familyFileId))
            ->get();
    }

    public function corporateMembers(int $familyFileId)
    {
        return $this->model
            ->where('family_file_id', $familyFileId)
            ->get();
    }
}
