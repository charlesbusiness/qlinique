<?php

namespace App\Repositories;

use App\Models\Invoice;

class InvoiceRepository extends BaseRepository
{
    public function __construct(Invoice $model)
    {
        parent::__construct($model);
    }

    public function pendingInvoices()
    {
        return $this->model
            ->whereIn('status', ['pending', 'partial'])
            ->with('patientFile', 'patient')
            ->latest()
            ->paginate(15);
    }

    public function forPatient(int $patientId)
    {
        return $this->model
            ->where('patient_id', $patientId)
            ->with('payments', 'patientFile')
            ->latest()
            ->get();
    }
}
