<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Patient;
use App\Services\FinanceService;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function __construct(
        protected FinanceService $financeService
    ) {}

    public function invoices()
    {
        $invoices = Invoice::with('patient')->latest()->paginate(15);
        return view('finance.invoices', compact('invoices'));
    }

    public function createInvoice()
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        return view('finance.create-invoice', compact('patients'));
    }

    public function storeInvoice(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'treatment_chart_id' => 'nullable|exists:treatment_charts,id',
            'amount_due' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $patient = Patient::findOrFail($data['patient_id']);
        $data['account_type'] = $patient->account_type;

        $this->financeService->generateInvoice($data);

        return redirect()->route('finance.invoices')
            ->with('status', 'Invoice generated successfully.');
    }

    public function showInvoice(Invoice $invoice)
    {
        $invoice->load('patient', 'payments', 'treatmentChart');
        return view('finance.show-invoice', compact('invoice'));
    }

    public function payments()
    {
        $invoices = Invoice::whereIn('status', ['pending', 'partial'])
            ->with('patient')
            ->latest()
            ->paginate(15);

        return view('finance.payments', compact('invoices'));
    }

    public function storePayment(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,mobile_money,bank_transfer',
            'notes' => 'nullable|string',
        ]);

        $this->financeService->recordPayment($invoice, $data);

        return redirect()->route('finance.invoices')
            ->with('status', 'Payment recorded successfully.');
    }
}
