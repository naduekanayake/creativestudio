<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::with('client')->latest()->paginate(10);

        $stats = [
            'total'    => Quotation::count(),
            'accepted' => Quotation::where('status', 'Accepted')->count(),
            'sent'     => Quotation::where('status', 'Sent')->count(),
            'draft'    => Quotation::where('status', 'Draft')->count(),
        ];

        return view('quotations.index', compact('quotations', 'stats'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $nextNumber = 'QUO-' . date('Y') . '-' . str_pad((Quotation::count() + 1), 4, '0', STR_PAD_LEFT);
        return view('quotations.create', compact('clients', 'nextNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id'         => 'required|exists:clients,id',
            'quotation_number'  => 'required|string|unique:quotations,quotation_number',
            'project_event'     => 'nullable|string|max:255',
            'issue_date'        => 'required|date',
            'valid_until'       => 'required|date',
            'discount_percent'  => 'nullable|numeric|min:0|max:100',
            'vat_percent'       => 'nullable|numeric|min:0|max:100',
            'terms'             => 'nullable|string',
            'payment_terms'     => 'nullable|string',
            'status'            => 'required|in:Draft,Sent,Accepted,Rejected,Expired',
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

            $quotation = Quotation::create([
                'quotation_number' => $validated['quotation_number'],
                'client_id'        => $validated['client_id'],
                'project_event'    => $validated['project_event'],
                'issue_date'       => $validated['issue_date'],
                'valid_until'      => $validated['valid_until'],
                'sub_total'        => $subTotal,
                'discount_percent' => $validated['discount_percent'] ?? 0,
                'vat_percent'      => $validated['vat_percent'] ?? 0,
                'total_amount'     => $total,
                'terms'            => $validated['terms'],
                'payment_terms'    => $validated['payment_terms'],
                'status'           => $validated['status'],
            ]);

            foreach ($validated['items'] as $item) {
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'item_name'    => $item['item_name'],
                    'description'  => $item['description'] ?? null,
                    'qty'          => $item['qty'],
                    'unit_price'   => $item['unit_price'],
                    'total'        => $item['qty'] * $item['unit_price'],
                ]);
            }
        });

        return redirect()->route('quotations.index')
            ->with('success', 'Quotation created successfully!');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load('client', 'items');
        return view('quotations.show', compact('quotation'));
    }

    public function edit(Quotation $quotation)
    {
        $quotation->load('items');
        $clients = Client::orderBy('name')->get();
        return view('quotations.edit', compact('quotation', 'clients'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'client_id'         => 'required|exists:clients,id',
            'project_event'     => 'nullable|string|max:255',
            'issue_date'        => 'required|date',
            'valid_until'       => 'required|date',
            'discount_percent'  => 'nullable|numeric|min:0|max:100',
            'vat_percent'       => 'nullable|numeric|min:0|max:100',
            'terms'             => 'nullable|string',
            'payment_terms'     => 'nullable|string',
            'status'            => 'required|in:Draft,Sent,Accepted,Rejected,Expired',
            'items'             => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.qty'       => 'required|integer|min:1',
            'items.*.unit_price'=> 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $quotation) {
            $subTotal = 0;
            foreach ($validated['items'] as $item) {
                $subTotal += $item['qty'] * $item['unit_price'];
            }

            $discount = $subTotal * (($validated['discount_percent'] ?? 0) / 100);
            $afterDiscount = $subTotal - $discount;
            $vat = $afterDiscount * (($validated['vat_percent'] ?? 0) / 100);
            $total = $afterDiscount + $vat;

            $quotation->update([
                'client_id'        => $validated['client_id'],
                'project_event'    => $validated['project_event'],
                'issue_date'       => $validated['issue_date'],
                'valid_until'      => $validated['valid_until'],
                'sub_total'        => $subTotal,
                'discount_percent' => $validated['discount_percent'] ?? 0,
                'vat_percent'      => $validated['vat_percent'] ?? 0,
                'total_amount'     => $total,
                'terms'            => $validated['terms'],
                'payment_terms'    => $validated['payment_terms'],
                'status'           => $validated['status'],
            ]);

            $quotation->items()->delete();

            foreach ($validated['items'] as $item) {
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'item_name'    => $item['item_name'],
                    'description'  => $item['description'] ?? null,
                    'qty'          => $item['qty'],
                    'unit_price'   => $item['unit_price'],
                    'total'        => $item['qty'] * $item['unit_price'],
                ]);
            }
        });

        return redirect()->route('quotations.show', $quotation)
            ->with('success', 'Quotation updated successfully!');
    }

    public function updateStatus(Request $request, Quotation $quotation)
    {
        $request->validate([
            'status' => 'required|in:Draft,Sent,Accepted,Rejected,Expired',
        ]);

        $quotation->update(['status' => $request->status]);

        return back()->with('success', 'Status updated to ' . $request->status);
    }

    public function destroy(Quotation $quotation)
    {
        $quotation->delete();
        return redirect()->route('quotations.index')
            ->with('success', 'Quotation deleted successfully!');
    }
}