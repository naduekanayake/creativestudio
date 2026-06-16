<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Client;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('client')->latest()->paginate(10);

        $stats = [
            'total'      => Invoice::count(),
            'paid'       => Invoice::where('payment_status', 'Paid')->count(),
            'unpaid'     => Invoice::where('payment_status', 'Unpaid')->count(),
            'total_due'  => Invoice::whereIn('payment_status', ['Unpaid', 'Partial'])
                                ->get()
                                ->sum(fn($inv) => $inv->total_amount - $inv->paid_amount),
        ];

        return view('invoices.index', compact('invoices', 'stats'));
    }

    public function create(Request $request)
    {
        $clients = Client::orderBy('name')->get();
        $nextNumber = 'INV-' . date('Y') . '-' . str_pad((Invoice::count() + 1), 4, '0', STR_PAD_LEFT);

        $fromQuotation = null;
        if ($request->has('quotation_id')) {
            $fromQuotation = Quotation::with('client', 'items')->find($request->quotation_id);
        }

        return view('invoices.create', compact('clients', 'nextNumber', 'fromQuotation'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id'         => 'required|exists:clients,id',
            'quotation_id'      => 'nullable|exists:quotations,id',
            'invoice_number'    => 'required|string|unique:invoices,invoice_number',
            'type'              => 'required|in:Advance,Tax,Final',
            'project_event'     => 'nullable|string|max:255',
            'issue_date'        => 'required|date',
            'due_date'          => 'required|date',
            'discount_percent'  => 'nullable|numeric|min:0|max:100',
            'vat_percent'       => 'nullable|numeric|min:0|max:100',
            'paid_amount'       => 'nullable|numeric|min:0',
            'terms'             => 'nullable|string',
            'payment_terms'     => 'nullable|string',
            'status'            => 'required|in:Draft,Sent,Paid,Overdue,Cancelled',
            'items'             => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.qty'       => 'required|integer|min:1',
            'items.*.unit_price'=> 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $subTotal = 0;
            foreach ($validated['items'] as $item) {
                $subTotal += $item['qty'] * $item['unit_price'];
            }

            $discount = $subTotal * (($validated['discount_percent'] ?? 0) / 100);
            $afterDiscount = $subTotal - $discount;
            $vat = $afterDiscount * (($validated['vat_percent'] ?? 0) / 100);
            $total = $afterDiscount + $vat;

            $paidAmount = $validated['paid_amount'] ?? 0;
            $paymentStatus = 'Unpaid';
            if ($paidAmount >= $total && $total > 0) {
                $paymentStatus = 'Paid';
            } elseif ($paidAmount > 0) {
                $paymentStatus = 'Partial';
            }

            $invoice = Invoice::create([
                'invoice_number'   => $validated['invoice_number'],
                'type'             => $validated['type'],
                'client_id'        => $validated['client_id'],
                'quotation_id'     => $validated['quotation_id'] ?? null,
                'project_event'    => $validated['project_event'],
                'issue_date'       => $validated['issue_date'],
                'due_date'         => $validated['due_date'],
                'sub_total'        => $subTotal,
                'discount_percent' => $validated['discount_percent'] ?? 0,
                'vat_percent'      => $validated['vat_percent'] ?? 0,
                'total_amount'     => $total,
                'paid_amount'      => $paidAmount,
                'payment_status'   => $paymentStatus,
                'terms'            => $validated['terms'],
                'payment_terms'    => $validated['payment_terms'],
                'status'           => $validated['status'],
            ]);

            foreach ($validated['items'] as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_name'  => $item['item_name'],
                    'description'=> $item['description'] ?? null,
                    'qty'        => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'total'      => $item['qty'] * $item['unit_price'],
                ]);
            }
        });

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully!');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('client', 'items', 'quotation');
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items');
        $clients = Client::orderBy('name')->get();
        return view('invoices.edit', compact('invoice', 'clients'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'client_id'         => 'required|exists:clients,id',
            'type'              => 'required|in:Advance,Tax,Final',
            'project_event'     => 'nullable|string|max:255',
            'issue_date'        => 'required|date',
            'due_date'          => 'required|date',
            'discount_percent'  => 'nullable|numeric|min:0|max:100',
            'vat_percent'       => 'nullable|numeric|min:0|max:100',
            'paid_amount'       => 'nullable|numeric|min:0',
            'terms'             => 'nullable|string',
            'payment_terms'     => 'nullable|string',
            'status'            => 'required|in:Draft,Sent,Paid,Overdue,Cancelled',
            'items'             => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.qty'       => 'required|integer|min:1',
            'items.*.unit_price'=> 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $invoice) {
            $subTotal = 0;
            foreach ($validated['items'] as $item) {
                $subTotal += $item['qty'] * $item['unit_price'];
            }

            $discount = $subTotal * (($validated['discount_percent'] ?? 0) / 100);
            $afterDiscount = $subTotal - $discount;
            $vat = $afterDiscount * (($validated['vat_percent'] ?? 0) / 100);
            $total = $afterDiscount + $vat;

            $paidAmount = $validated['paid_amount'] ?? 0;
            $paymentStatus = 'Unpaid';
            if ($paidAmount >= $total && $total > 0) {
                $paymentStatus = 'Paid';
            } elseif ($paidAmount > 0) {
                $paymentStatus = 'Partial';
            }

            $invoice->update([
                'client_id'        => $validated['client_id'],
                'type'             => $validated['type'],
                'project_event'    => $validated['project_event'],
                'issue_date'       => $validated['issue_date'],
                'due_date'         => $validated['due_date'],
                'sub_total'        => $subTotal,
                'discount_percent' => $validated['discount_percent'] ?? 0,
                'vat_percent'      => $validated['vat_percent'] ?? 0,
                'total_amount'     => $total,
                'paid_amount'      => $paidAmount,
                'payment_status'   => $paymentStatus,
                'terms'            => $validated['terms'],
                'payment_terms'    => $validated['payment_terms'],
                'status'           => $validated['status'],
            ]);

            $invoice->items()->delete();

            foreach ($validated['items'] as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_name'  => $item['item_name'],
                    'description'=> $item['description'] ?? null,
                    'qty'        => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'total'      => $item['qty'] * $item['unit_price'],
                ]);
            }
        });

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully!');
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:Draft,Sent,Paid,Overdue,Cancelled',
        ]);

        $invoice->update(['status' => $request->status]);

        return back()->with('success', 'Status updated to ' . $request->status);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully!');
    }
}