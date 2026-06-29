<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Repositories\InvoiceRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FinanceService
{
    public function __construct(
        protected InvoiceRepository $repository
    ) {}

    public function generateInvoice(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            $data['invoice_number'] = $this->nextInvoiceNumber();
            $data['balance'] = $data['amount_due'] - ($data['amount_paid'] ?? 0);
            $data['status'] = $data['balance'] <= 0 ? 'paid' : 'pending';

            return $this->repository->create($data);
        });
    }

    public function recordPayment(Invoice $invoice, array $data): Payment
    {
        return DB::transaction(function () use ($invoice, $data) {
            $data['receipt_number'] = $data['receipt_number'] ?? $this->nextReceiptNumber();

            $payment = $invoice->payments()->create($data);

            $totalPaid = $invoice->payments()->sum('amount');
            $invoice->update([
                'amount_paid' => $totalPaid,
                'balance' => $invoice->amount_due - $totalPaid,
                'status' => $invoice->amount_due - $totalPaid <= 0 ? 'paid' : 'partial',
            ]);

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
