<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Client;
use App\Models\Quotation;
use App\Models\Package;
use App\Models\Reminder;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        // Auto-overdue: due_date පහු වුණ unpaid/partial invoices → Overdue
        Invoice::whereIn('payment_status', ['Unpaid', 'Partial'])
            ->whereNotIn('status', ['Cancelled', 'Paid'])
            ->whereDate('due_date', '<', today())
            ->update(['status' => 'Overdue']);

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
        $packages = Package::orderBy('name')->get();
        $nextNumber = 'INV-' . date('Y') . '-' . str_pad((Invoice::count() + 1), 4, '0', STR_PAD_LEFT);

        $fromQuotation = null;
        if ($request->filled('quotation_id')) {
            $fromQuotation = Quotation::with('client')->find($request->quotation_id);
        }

        return view('invoices.create', compact('clients', 'packages', 'nextNumber', 'fromQuotation'));
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

        $this->createPaymentReminder($invoice);

        ActivityLog::log('created', 'Invoice', $invoice->id, $invoice->invoice_number,
            'Invoice created: ' . $invoice->invoice_number . ' for ' . $invoice->client->name,
            'receipt', 'purple');

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully!');
    }

    public function show(Invoice $invoice)
    {
        // Auto-overdue: මේ invoice එක due_date පහු වෙලා unpaid නම්
        if (in_array($invoice->payment_status, ['Unpaid', 'Partial'])
            && !in_array($invoice->status, ['Cancelled', 'Paid'])
            && $invoice->due_date
            && Carbon::parse($invoice->due_date)->lt(today())
            && $invoice->status !== 'Overdue') {
            $invoice->update(['status' => 'Overdue']);
        }

        $invoice->load('client', 'items');
        return view('invoices.show', compact('invoice'));
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load('client', 'items');
        $pdf = $this->generatePdf($invoice);
        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    /**
     * Public invoice view — login නැතුව, share_token එකෙන් customer ට බලන්න.
     */
    public function publicView(string $token)
    {
        $invoice = Invoice::where('share_token', $token)->firstOrFail();
        $invoice->load('client', 'items');
        return view('invoices.public', compact('invoice'));
    }

    /**
     * Public PDF download — login නැතුව.
     */
    public function publicPdf(string $token)
    {
        $invoice = Invoice::where('share_token', $token)->firstOrFail();
        $invoice->load('client', 'items');
        $pdf = $this->generatePdf($invoice);
        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    /**
     * Invoice PDF එක generate කරනවා (download + public + email වලට පොදුවේ).
     */
    private function generatePdf(Invoice $invoice)
    {
        $logoData = null;
        $logoPath = Setting::get('logo_path');
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            $fullPath = Storage::disk('public')->path($logoPath);
            $type = pathinfo($fullPath, PATHINFO_EXTENSION);
            $contents = file_get_contents($fullPath);
            $logoData = 'data:image/' . $type . ';base64,' . base64_encode($contents);
        }

        $data = [
            'invoice'       => $invoice,
            'logoData'      => $logoData,
            'studioName'    => Setting::get('studio_name', 'Creative Studio'),
            'studioTagline' => Setting::get('studio_tagline', 'Photography & Films'),
            'studioAddress' => Setting::get('address', ''),
            'studioCity'    => Setting::get('city', ''),
            'studioPhone'   => Setting::get('phone', ''),
            'studioEmail'   => Setting::get('email', ''),
            'bankName'      => Setting::get('bank_name', ''),
            'bankAccount'   => Setting::get('bank_account', ''),
            'bankBranch'    => Setting::get('bank_branch', ''),
            'invoiceFooter' => Setting::get('invoice_footer', ''),
        ];

        return Pdf::loadView('invoices.pdf', $data)->setPaper('a4');
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
        Reminder::where('source_type', 'invoice')->where('source_id', $invoice->id)->delete();

        $number = $invoice->invoice_number;
        $invoice->items()->delete();
        $invoice->delete();

        ActivityLog::log('deleted', 'Invoice', null, $number,
            'Invoice deleted: ' . $number, 'receipt', 'red');

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully!');
    }

    private function createPaymentReminder(Invoice $invoice): void
    {
        $existing = Reminder::where('source_type', 'invoice')
            ->where('source_id', $invoice->id)
            ->first();

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
	public function sendEmail(Invoice $invoice)
    {
        $invoice->load('client', 'items');

        if (empty($invoice->client->email)) {
            return back()->with('error', 'Client has no email address. Add one in the client profile first.');
        }

        $pdf = $this->generatePdf($invoice);
        $studioName = Setting::get('studio_name', 'Creative Studio');

        $lines = [
            'Please find attached your invoice ' . $invoice->invoice_number . '.',
            'Total Amount: Rs. ' . number_format($invoice->total_amount, 2),
            'Balance Due: Rs. ' . number_format($invoice->total_amount - $invoice->paid_amount, 2),
            'Due Date: ' . ($invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A'),
        ];

        try {
            \Illuminate\Support\Facades\Mail::to($invoice->client->email)->send(
                new \App\Mail\DocumentMail(
                    'Invoice',
                    $invoice->invoice_number,
                    $invoice->client->name,
                    $studioName,
                    $lines,
                    $pdf->output(),
                    $invoice->invoice_number . '.pdf'
                )
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Email failed: ' . $e->getMessage());
        }

        ActivityLog::log('emailed', 'Invoice', $invoice->id, $invoice->invoice_number,
            'Invoice emailed to ' . $invoice->client->email, 'mail', 'blue');

        return back()->with('success', 'Invoice emailed to ' . $invoice->client->email);
    }
}


