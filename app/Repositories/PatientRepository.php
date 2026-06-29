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

    public function familyMembers(int $accountHolderId)
    {
        return $this->model
            ->where('account_holder_id', $accountHolderId)
            ->orWhere('id', $accountHolderId)
            ->get();
    }

    public function corporateMembers(int $accountHolderId)
    {
        return $this->model
            ->where('account_holder_id', $accountHolderId)
            ->get();
    }
}
