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
                if ($treatment->vitals()->exists()) {
                    $treatment->vitals()->first()->update($data['vitals']);
                } else {
                    $treatment->vitals()->create($data['vitals']);
                }
            }

            return $treatment->fresh(['patient', 'vitals', 'medications', 'labTests']);
        });
    }

    public function complete(TreatmentChart $treatment): void
    {
        $this->repository->update($treatment, ['is_completed' => true]);
    }

    public function createDraft(int $patientId, string $category, ?string $subCategory = null, ?int $userId = null): TreatmentChart
    {
        return DB::transaction(function () use ($patientId, $category, $subCategory, $userId) {
            return $this->repository->create([
                'patient_id' => $patientId,
                'category' => $category,
                'sub_category' => $subCategory,
                'visit_date' => now()->format('Y-m-d'),
                'is_draft' => true,
                'current_step' => 1,
                'created_by' => $userId,
            ]);
        });
    }

    public function findDraftForUser(int $userId): ?TreatmentChart
    {
        return TreatmentChart::where('is_draft', true)
            ->where('is_completed', false)
            ->where('created_by', $userId)
            ->latest()
            ->first();
    }

    public function saveStep(TreatmentChart $draft, int $step, array $data): TreatmentChart
    {
        return DB::transaction(function () use ($draft, $step, $data) {
            $chartData = array_merge($data, ['current_step' => $step]);
            unset($chartData['vitals'], $chartData['physical_examinations'], $chartData['rme_results'], $chartData['lab_tests'], $chartData['treatment_plan_items']);

            $this->repository->update($draft, $chartData);

            if (isset($data['vitals'])) {
                if ($draft->vitals()->exists()) {
                    $draft->vitals()->first()->update($data['vitals']);
                } else {
                    $draft->vitals()->create($data['vitals']);
                }
            }

            if (isset($data['physical_examinations'])) {
                $draft->physicalExaminations()->delete();
                foreach ($data['physical_examinations'] as $exam) {
                    $draft->physicalExaminations()->create($exam);
                }
            }

            if (isset($data['rme_results'])) {
                $draft->rmeResults()->delete();
                foreach ($data['rme_results'] as $rme) {
                    $draft->rmeResults()->create($rme);
                }
            }

            if (isset($data['lab_tests'])) {
                $draft->labTests()->delete();
                foreach ($data['lab_tests'] as $lab) {
                    $draft->labTests()->create($lab);
                }
            }

            if (isset($data['treatment_plan_items'])) {
                $draft->treatmentPlanItems()->delete();
                foreach ($data['treatment_plan_items'] as $item) {
                    $draft->treatmentPlanItems()->create($item);
                }
            }

            return $draft->fresh();
        });
    }

    public function publishDraft(TreatmentChart $draft): TreatmentChart
    {
        return DB::transaction(function () use ($draft) {
            $this->repository->update($draft, [
                'is_draft' => false,
                'is_completed' => true,
            ]);
            return $draft->fresh();
        });
    }

    public function discardDraft(TreatmentChart $draft): void
    {
        $draft->delete();
    }
}
