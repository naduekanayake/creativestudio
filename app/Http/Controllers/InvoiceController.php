<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Client;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('client')->latest()->paginate(15);
        $stats = [
            'total'   => Invoice::count(),
            'paid'    => Invoice::where('payment_status', 'Paid')->count(),
            'partial' => Invoice::where('payment_status', 'Partial')->count(),
            'unpaid'  => Invoice::where('payment_status', 'Unpaid')->count(),
            'overdue' => Invoice::where('payment_status', '!=', 'Paid')
                ->where('due_date', '<', today())->count(),
        ];
        return view('invoices.index', compact('invoices', 'stats'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $nextNumber = 'INV-' . date('Y') . '-' . str_pad((Invoice::count() + 1), 4, '0', STR_PAD_LEFT);
        return view('invoices.create', compact('clients', 'nextNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id'        => 'required|exists:clients,id',
            'invoice_number'   => 'required|string|unique:invoices,invoice_number',
            'type'             => 'required|in:Tax,Advance,Final',
            'project_event'    => 'nullable|string|max:255',
            'issue_date'       => 'required|date',
            'due_date'         => 'required|date',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'vat_percent'      => 'nullable|numeric|min:0|max:100',
            'paid_amount'      => 'nullable|numeric|min:0',
            'payment_terms'    => 'nullable|string',
            'terms'            => 'nullable|string',
            'status'           => 'required|in:Draft,Sent,Paid,Overdue,Cancelled',
            'items'            => 'required|array|min:1',
            'items.*.item_name'  => 'required|string',
            'items.*.qty'        => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Calculate totals
        $subTotal = 0;
        foreach ($request->items as $item) {
            $subTotal += $item['qty'] * $item['unit_price'];
        }
        $discount = $subTotal * (($request->discount_percent ?? 0) / 100);
        $afterDiscount = $subTotal - $discount;
        $vat = $afterDiscount * (($request->vat_percent ?? 0) / 100);
        $totalAmount = $afterDiscount + $vat;
        $paidAmount = $request->paid_amount ?? 0;

        $paymentStatus = 'Unpaid';
        if ($paidAmount >= $totalAmount && $totalAmount > 0) {
            $paymentStatus = 'Paid';
        } elseif ($paidAmount > 0) {
            $paymentStatus = 'Partial';
        }

        $invoice = Invoice::create([
            'client_id'        => $request->client_id,
            'invoice_number'   => $request->invoice_number,
            'type'             => $request->type,
            'project_event'    => $request->project_event,
            'issue_date'       => $request->issue_date,
            'due_date'         => $request->due_date,
            'discount_percent' => $request->discount_percent ?? 0,
            'vat_percent'      => $request->vat_percent ?? 0,
            'sub_total'        => $subTotal,
            'discount_amount'  => $discount,
            'vat_amount'       => $vat,
            'total_amount'     => $totalAmount,
            'paid_amount'      => $paidAmount,
            'payment_status'   => $paymentStatus,
            'payment_terms'    => $request->payment_terms,
            'terms'            => $request->terms,
            'status'           => $request->status,
        ]);

        foreach ($request->items as $item) {
            InvoiceItem::create([
                'invoice_id'  => $invoice->id,
                'item_name'   => $item['item_name'],
                'description' => $item['description'] ?? null,
                'qty'         => $item['qty'],
                'unit_price'  => $item['unit_price'],
                'total'       => $item['qty'] * $item['unit_price'],
            ]);
        }

        ActivityLog::log('created', 'Invoice', $invoice->id, $invoice->invoice_number,
            'Invoice created: ' . $invoice->invoice_number . ' for ' . $invoice->client->name,
            'receipt', 'purple');

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully!');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('client', 'items');
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $clients = Client::orderBy('name')->get();
        $invoice->load('items', 'client');
        return view('invoices.edit', compact('invoice', 'clients'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'client_id'        => 'required|exists:clients,id',
            'type'             => 'required|in:Tax,Advance,Final',
            'project_event'    => 'nullable|string|max:255',
            'issue_date'       => 'required|date',
            'due_date'         => 'required|date',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'vat_percent'      => 'nullable|numeric|min:0|max:100',
            'paid_amount'      => 'nullable|numeric|min:0',
            'payment_terms'    => 'nullable|string',
            'terms'            => 'nullable|string',
            'status'           => 'required|in:Draft,Sent,Paid,Overdue,Cancelled',
            'items'            => 'required|array|min:1',
            'items.*.item_name'  => 'required|string',
            'items.*.qty'        => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $subTotal = 0;
        foreach ($request->items as $item) {
            $subTotal += $item['qty'] * $item['unit_price'];
        }
        $discount = $subTotal * (($request->discount_percent ?? 0) / 100);
        $afterDiscount = $subTotal - $discount;
        $vat = $afterDiscount * (($request->vat_percent ?? 0) / 100);
        $totalAmount = $afterDiscount + $vat;
        $paidAmount = $request->paid_amount ?? 0;

        $paymentStatus = 'Unpaid';
        if ($paidAmount >= $totalAmount && $totalAmount > 0) {
            $paymentStatus = 'Paid';
        } elseif ($paidAmount > 0) {
            $paymentStatus = 'Partial';
        }

        $invoice->update([
            'client_id'        => $request->client_id,
            'type'             => $request->type,
            'project_event'    => $request->project_event,
            'issue_date'       => $request->issue_date,
            'due_date'         => $request->due_date,
            'discount_percent' => $request->discount_percent ?? 0,
            'vat_percent'      => $request->vat_percent ?? 0,
            'sub_total'        => $subTotal,
            'discount_amount'  => $discount,
            'vat_amount'       => $vat,
            'total_amount'     => $totalAmount,
            'paid_amount'      => $paidAmount,
            'payment_status'   => $paymentStatus,
            'payment_terms'    => $request->payment_terms,
            'terms'            => $request->terms,
            'status'           => $request->status,
        ]);

        $invoice->items()->delete();
        foreach ($request->items as $item) {
            InvoiceItem::create([
                'invoice_id'  => $invoice->id,
                'item_name'   => $item['item_name'],
                'description' => $item['description'] ?? null,
                'qty'         => $item['qty'],
                'unit_price'  => $item['unit_price'],
                'total'       => $item['qty'] * $item['unit_price'],
            ]);
        }

        ActivityLog::log('updated', 'Invoice', $invoice->id, $invoice->invoice_number,
            'Invoice updated: ' . $invoice->invoice_number, 'receipt', 'orange');

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully!');
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:Draft,Sent,Paid,Overdue,Cancelled',
        ]);
        $invoice->update(['status' => $request->status]);

        ActivityLog::log('updated', 'Invoice', $invoice->id, $invoice->invoice_number,
            'Invoice status changed to: ' . $request->status, 'receipt', 'orange');

        return back()->with('success', 'Invoice status updated!');
    }

    public function destroy(Invoice $invoice)
    {
        $number = $invoice->invoice_number;
        $invoice->items()->delete();
        $invoice->delete();

        ActivityLog::log('deleted', 'Invoice', null, $number,
            'Invoice deleted: ' . $number, 'receipt', 'red');

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully!');
    }
}