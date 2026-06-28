<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('client', 'invoice')->latest()->paginate(15);
        $stats = [
            'total'        => Payment::count(),
            'completed'    => Payment::where('status', 'Completed')->count(),
            'pending'      => Payment::where('status', 'Pending')->count(),
            'total_amount' => Payment::where('status', 'Completed')->sum('amount'),
        ];
        return view('payments.index', compact('payments', 'stats'));
    }

    public function create(Request $request)
    {
        $clients = Client::orderBy('name')->get();
        $invoices = collect();
        if ($request->has('invoice_id')) {
            $invoice = Invoice::with('client')->find($request->invoice_id);
            if ($invoice) {
                $invoices = Invoice::where('client_id', $invoice->client_id)
                    ->whereIn('payment_status', ['Unpaid', 'Partial'])->get();
            }
        }
        $nextNumber = 'PAY-' . date('Y') . '-' . str_pad((Payment::max('id') + 1), 4, '0', STR_PAD_LEFT);
        $selectedInvoiceId = $request->invoice_id ?? null;
        return view('payments.create', compact('clients', 'invoices', 'nextNumber', 'selectedInvoiceId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_number' => 'required|string|unique:payments,payment_number',
            'client_id'      => 'required|exists:clients,id',
            'invoice_id'     => 'nullable|exists:invoices,id',
            'amount'         => 'required|numeric|min:0.01',
            'method'         => 'required|in:Cash,Bank Transfer,Cheque,Online,Card',
            'payment_date'   => 'required|date',
            'notes'          => 'nullable|string',
            'status'         => 'required|in:Completed,Pending,Failed,Refunded',
        ]);

        $payment = Payment::create($validated);

        if ($payment->invoice_id && $payment->status === 'Completed') {
            $this->recalculateInvoice($payment->invoice_id);
        }

        ActivityLog::log('created', 'Payment', $payment->id, $payment->payment_number,
            'Payment recorded: Rs. ' . number_format($payment->amount) . ' from ' . $payment->client->name,
            'credit-card', 'green');

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully!');
    }

    public function show(Payment $payment)
    {
        $payment->load('client', 'invoice');
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $clients = Client::orderBy('name')->get();
        $invoices = Invoice::where('client_id', $payment->client_id)
            ->whereIn('payment_status', ['Unpaid', 'Partial'])->get();
        return view('payments.edit', compact('payment', 'clients', 'invoices'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'client_id'    => 'required|exists:clients,id',
            'invoice_id'   => 'nullable|exists:invoices,id',
            'amount'       => 'required|numeric|min:0.01',
            'method'       => 'required|in:Cash,Bank Transfer,Cheque,Online,Card',
            'payment_date' => 'required|date',
            'notes'        => 'nullable|string',
            'status'       => 'required|in:Completed,Pending,Failed,Refunded',
        ]);

        $oldInvoiceId = $payment->invoice_id;
        $payment->update($validated);

        if ($oldInvoiceId) $this->recalculateInvoice($oldInvoiceId);
        if ($payment->invoice_id && $payment->invoice_id != $oldInvoiceId) {
            $this->recalculateInvoice($payment->invoice_id);
        } elseif ($payment->invoice_id) {
            $this->recalculateInvoice($payment->invoice_id);
        }

        ActivityLog::log('updated', 'Payment', $payment->id, $payment->payment_number,
            'Payment updated: ' . $payment->payment_number, 'credit-card', 'orange');

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment updated successfully!');
    }

    public function destroy(Payment $payment)
    {
        $invoiceId = $payment->invoice_id;
        $number = $payment->payment_number;
        $payment->delete();

        if ($invoiceId) $this->recalculateInvoice($invoiceId);

        ActivityLog::log('deleted', 'Payment', null, $number,
            'Payment deleted: ' . $number, 'credit-card', 'red');

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully!');
    }

    private function recalculateInvoice(int $invoiceId): void
    {
        $invoice = Invoice::find($invoiceId);
        if (!$invoice) return;
        $totalPaid = Payment::where('invoice_id', $invoiceId)
            ->where('status', 'Completed')->sum('amount');
        $invoice->paid_amount = $totalPaid;
        $invoice->payment_status = $totalPaid >= $invoice->total_amount
            ? 'Paid' : ($totalPaid > 0 ? 'Partial' : 'Unpaid');
        $invoice->save();
    }
}