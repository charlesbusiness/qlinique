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
        return $this->model->whereHas('file', fn($q) => $q->where('file_number', $fileNumber))->first();
    }

    public function search(string $query)
    {
        return $this->model
            ->where('name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhereHas('file', fn($q) => $q->where('file_number', 'like', "%{$query}%"))
            ->latest()
            ->paginate(15);
    }

    public function familyMembers(int $fileId)
    {
        return $this->model
            ->where('file_id', $fileId)
            ->orWhereHas('file', fn($q) => $q->where('id', $fileId))
            ->get();
    }

    public function corporateMembers(int $fileId)
    {
        return $this->model
            ->where('file_id', $fileId)
            ->get();
    }
}
