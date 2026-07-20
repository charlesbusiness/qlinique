<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Repositories\InvoiceRepository;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    public function __construct(
        protected InvoiceRepository $repository
    ) {}

    public function generateInvoice(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            $items = $data['items'] ?? [];
            unset($data['items']);

            $data['invoice_number'] = $this->nextInvoiceNumber();
            $data['balance'] = $data['amount_due'] - ($data['amount_paid'] ?? 0);
            $data['status'] = $data['balance'] <= 0 ? 'paid' : 'pending';

            $invoice = $this->repository->create($data);

            foreach ($items as $item) {
                $invoice->items()->create($item);
            }

            return $invoice;
        });
    }

    public function updateInvoice(Invoice $invoice, array $data): Invoice
    {
        return DB::transaction(function () use ($invoice, $data) {
            $items = $data['items'] ?? [];
            unset($data['items']);

            $data['balance'] = $data['amount_due'] - ($data['amount_paid'] ?? 0);
            $data['status'] = $data['balance'] <= 0 ? 'paid' : 'pending';

            $invoice->update($data);

            $invoice->items()->delete();
            foreach ($items as $item) {
                $invoice->items()->create($item);
            }

            return $invoice;
        });
    }

    public function recordPayment(Invoice $invoice, array $data): Payment
    {
        return DB::transaction(function () use ($invoice, $data) {
            $data['receipt_number'] = $data['receipt_number'] ?? $this->nextReceiptNumber();

            $payment = $invoice->payments()->create($data);

            $totalPaid = $invoice->payments()->sum('amount');
            $balance = $invoice->amount_due - $totalPaid;
            $invoice->update([
                'amount_paid' => $totalPaid,
                'balance' => $balance,
                'status' => $balance <= 0 ? 'paid' : 'partial',
            ]);

            if ($invoice->treatment_chart_id && $invoice->treatmentChart) {
                $chart = $invoice->treatmentChart;
                if ($chart->medical_bill) {
                    $bill = (array) $chart->medical_bill;
                    $bill['paid'] = $totalPaid;
                    $bill['outstanding'] = $balance;
                    $chart->update(['medical_bill' => $bill]);
                }

                if ($chart->maternalHealthRecord) {
                    $mhRecord = $chart->maternalHealthRecord;
                    $mhUpdate = [
                        'bill_paid' => $totalPaid,
                        'bill_outstanding' => $balance,
                    ];
                    if ($mhRecord->medical_bill) {
                        $mhBill = (array) $mhRecord->medical_bill;
                        $mhBill['paid'] = $totalPaid;
                        $mhBill['outstanding'] = $balance;
                        $mhUpdate['medical_bill'] = $mhBill;
                    }
                    $mhRecord->update($mhUpdate);
                }
            }

            return $payment;
        });
    }

    protected function nextInvoiceNumber(): string
    {
        $year = now()->year;
        $last = Invoice::whereYear('created_at', $year)->max('id') ?? 0;

        return sprintf('INV-%s-%05d', $year, $last + 1);
    }

    protected function nextReceiptNumber(): string
    {
        $year = now()->year;
        $last = Payment::whereYear('created_at', $year)->max('id') ?? 0;

        return sprintf('RCP-%s-%05d', $year, $last + 1);
    }
}
