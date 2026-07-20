<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\TreatmentChart;
use App\Repositories\TreatmentRepository;
use Illuminate\Support\Facades\DB;

class TreatmentService
{
    protected const BILL_CATEGORY_LABELS = [
        'registration' => 'Registration',
        'consultation' => 'Consultation',
        'rapid_medical_examination' => 'RME',
        'laboratory_test' => 'Lab Test',
        'admission' => 'Admission',
        'medical_service' => 'Medical Service',
        'logistics' => 'Logistics',
        'maintenance' => 'Maintenance',
        'surgical_procedure' => 'Surgical Procedure',
    ];

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
            ->where('created_at', '>=', now()->subHours(24))
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

    public function syncSchedule(TreatmentChart $chart): void
    {
        $maxItem = $chart->treatmentPlanItems()
            ->where('is_take_home', false)
            ->orderByRaw('CAST(length_value AS UNSIGNED) DESC')
            ->first();

        $schedule = $maxItem?->length_display;

        if ($chart->treatment_schedule !== $schedule) {
            $this->repository->update($chart, ['treatment_schedule' => $schedule]);
        }
    }

    public function publishDraft(TreatmentChart $draft): TreatmentChart
    {
        return DB::transaction(function () use ($draft) {
            $this->syncSchedule($draft);

            $this->repository->update($draft, [
                'is_draft' => false,
            ]);

            $this->syncInvoice($draft);

            return $draft->fresh();
        });
    }

    public function discardDraft(TreatmentChart $draft): void
    {
        $draft->delete();
    }

    public function syncInvoice(TreatmentChart $chart): void
    {
        $medicalBill = $chart->medical_bill;

        if (! $medicalBill || ! is_array($medicalBill)) {
            return;
        }

        $items = [];
        foreach (self::BILL_CATEGORY_LABELS as $key => $label) {
            $amount = (float) ($medicalBill[$key] ?? 0);
            if ($amount > 0) {
                $items[] = [
                    'description' => $label,
                    'category' => $key,
                    'amount' => $amount,
                ];
            }
        }

        $total = array_sum(array_column($items, 'amount'));

        if ($total <= 0) {
            return;
        }

        $paid = (float) ($medicalBill['paid'] ?? 0);
        $invoiceData = [
            'patient_id' => $chart->patient_id,
            'patient_file_id' => $chart->patient->file_id,
            'treatment_chart_id' => $chart->id,
            'amount_due' => $total,
            'amount_paid' => $paid,
            'items' => $items,
        ];

        $existingInvoice = $chart->invoices()->first();

        if ($existingInvoice) {
            $hasFinancialChanges = $existingInvoice->amount_due != $total
                || $existingInvoice->amount_paid != $paid;

            if ($hasFinancialChanges) {
                $invoiceData['amount_paid'] = $existingInvoice->payments()->sum('amount');
                $invoiceData['amount_due'] = $total;
                $invoiceData['balance'] = $total - $invoiceData['amount_paid'];
                $invoiceData['status'] = $invoiceData['balance'] <= 0 ? 'paid' : 'pending';

                app(FinanceService::class)->updateInvoice($existingInvoice, $invoiceData);
            }
        } else {
            app(FinanceService::class)->generateInvoice($invoiceData);
        }
    }
}
