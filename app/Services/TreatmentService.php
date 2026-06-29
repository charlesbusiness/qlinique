<?php

namespace App\Services;

use App\Models\TreatmentChart;
use App\Repositories\TreatmentRepository;
use Illuminate\Support\Facades\DB;

class TreatmentService
{
    public function __construct(
        protected TreatmentRepository $repository
    ) {}

    public function create(array $data): TreatmentChart
    {
        return DB::transaction(function () use ($data) {
            $treatment = $this->repository->create($data);

            if (isset($data['vitals'])) {
                $treatment->vitals()->create($data['vitals']);
            }

            if (isset($data['medications'])) {
                foreach ($data['medications'] as $medication) {
                    $medication['total_cost'] = ($medication['quantity'] ?? 0) * ($medication['unit_cost'] ?? 0);
                    $treatment->medications()->create($medication);
                }
            }

            if (isset($data['lab_tests'])) {
                foreach ($data['lab_tests'] as $labTest) {
                    $treatment->labTests()->create($labTest);
                }
            }

            return $treatment->fresh(['patient', 'vitals', 'medications', 'labTests']);
        });
    }

    public function update(TreatmentChart $treatment, array $data): TreatmentChart
    {
        return DB::transaction(function () use ($treatment, $data) {
            $this->repository->update($treatment, $data);

            if (isset($data['vitals'])) {
                $treatment->vitals()->delete();
                $treatment->vitals()->create($data['vitals']);
            }

            return $treatment->fresh(['patient', 'vitals', 'medications', 'labTests']);
        });
    }

    public function complete(TreatmentChart $treatment): void
    {
        $this->repository->update($treatment, ['is_completed' => true]);
    }
}
