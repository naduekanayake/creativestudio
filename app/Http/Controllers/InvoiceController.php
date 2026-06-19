<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Client;
use App\Models\Quotation;
use App\Models\Reminder;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('client')->latest()->paginate(15);
        $stats = [
            'total'     => Invoice::count(),
            'paid'      => Invoice::where('payment_status', 'Paid')->count(),
            'partial'   => Invoice::where('payment_status', 'Partial')->count(),
            'unpaid'    => Invoice::where('payment_status', 'Unpaid')->count(),
            'overdue'   => Invoice::where('payment_status', '!=', 'Paid')
                ->where('due_date', '<', today())->count(),
            'total_due' => Invoice::where('payment_status', '!=', 'Paid')
                ->selectRaw('SUM(total_amount - paid_amount) as due')
                ->value('due') ?? 0,
        ];
        return view('invoices.index', compact('invoices', 'stats'));
    }

    public function create(Request $request)
    {
        $clients = Client::orderBy('name')->get();
        $nextNumber = 'INV-' . date('Y') . '-' . str_pad((Invoice::count() + 1), 4, '0', STR_PAD_LEFT);

        // Quotation එකකින් invoice එකක් හදනවා නම් (?quotation_id=X)
        $fromQuotation = null;
        if ($request->filled('quotation_id')) {
            $fromQuotation = Quotation::with('client')->find($request->quotation_id);
        }

        return view('invoices.create', compact('clients', 'nextNumber', 'fromQuotation'));
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

        // Auto reminder — due date එකට link වෙච්ච payment reminder එකක්
        $this->createPaymentReminder($invoice);

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

        // Due date වෙනස් / paid වුණත් — linked reminder එක update/delete වෙනවා
        $this->createPaymentReminder($invoice);

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
        // මේ invoice එකේ auto reminder එකත් අයින් කරනවා
        Reminder::where('source_type', 'invoice')->where('source_id', $invoice->id)->delete();

        $number = $invoice->invoice_number;
        $invoice->items()->delete();
        $invoice->delete();

        ActivityLog::log('deleted', 'Invoice', null, $number,
            'Invoice deleted: ' . $number, 'receipt', 'red');

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully!');
    }

    /**
     * Invoice due date එකම remind_date විදිහට auto payment reminder.
     * source_type/source_id වලින් link — date වෙනස් / paid වුණොත් හරියට handle වෙනවා.
     */
    private function createPaymentReminder(Invoice $invoice): void
    {
        $existing = Reminder::where('source_type', 'invoice')
            ->where('source_id', $invoice->id)
            ->first();

        // Paid නම් / due date නැත්නම් / අතීතයේ නම් — පරණ එක තිබුණොත් අයින් කරනවා
        if ($invoice->payment_status === 'Paid'
            || !$invoice->due_date
            || Carbon::parse($invoice->due_date)->lt(Carbon::today())) {
            if ($existing) {
                $existing->delete();
            }
            return;
        }

        $data = [
            'source_type' => 'invoice',
            'source_id'   => $invoice->id,
            'title'       => 'Payment due: ' . $invoice->invoice_number,
            'description' => 'Payment for invoice ' . $invoice->invoice_number . ' is due. Follow up with client.',
            'client_id'   => $invoice->client_id,
            'type'        => 'Payment',
            'remind_date' => Carbon::parse($invoice->due_date)->toDateString(),
            'remind_time' => null,
            'status'      => 'Pending',
            'priority'    => 'High',
        ];

        if ($existing) {
            $existing->update($data);
        } else {
            Reminder::create($data);
        }
    }
}