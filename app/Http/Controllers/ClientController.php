<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(15);
        $stats = [
            'total'    => Client::count(),
            'active'   => Client::where('status', 'Active')->count(),
            'inactive' => Client::where('status', 'Inactive')->count(),
            'pending'  => Client::where('status', 'Pending')->count(),
        ];
        return view('clients.index', compact('clients', 'stats'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'nullable|email|max:255',
            'phone'       => 'nullable|string|max:20',
            'company'     => 'nullable|string|max:255',
            'type'        => 'nullable|string',
            'lead_source' => 'nullable|string|max:100',
            'address'     => 'nullable|string',
            'city'        => 'nullable|string|max:100',
            'website'     => 'nullable|string|max:255',
            'notes'       => 'nullable|string',
            'status'      => 'nullable|string',
        ]);

        $client = Client::create($validated);

        ActivityLog::log('created', 'Client', $client->id, $client->name,
            'New client added: ' . $client->name, 'users', 'blue');

        return redirect()->route('clients.index')
            ->with('success', 'Client created successfully!');
    }

    public function show(Client $client)
    {
        $client->load(['quotations', 'invoices', 'payments', 'jobs']);

        // ===== Client stats =====
        $totalProjects = $client->jobs->count();
        $totalRevenue = $client->payments->where('status', 'Completed')->sum('amount');
        $dueAmount = $client->invoices
            ->whereIn('payment_status', ['Unpaid', 'Partial'])
            ->sum(fn($inv) => $inv->total_amount - $inv->paid_amount);

        // ===== Timeline — ඔක්කොම activities එක array එකකට, date order =====
        $timeline = collect();

        foreach ($client->quotations as $q) {
            $timeline->push([
                'type'  => 'quotation',
                'date'  => $q->issue_date ?? $q->created_at,
                'title' => 'Quotation ' . $q->quotation_number,
                'sub'   => 'Rs. ' . number_format($q->total_amount, 2) . ' · ' . $q->status,
                'url'   => route('quotations.show', $q),
                'color' => 'blue',
            ]);
        }
        foreach ($client->jobs as $j) {
            $timeline->push([
                'type'  => 'job',
                'date'  => $j->event_date ?? $j->created_at,
                'title' => $j->title,
                'sub'   => ($j->job_number ?? '') . ' · ' . $j->status,
                'url'   => route('jobs.show', $j),
                'color' => 'pink',
            ]);
        }
        foreach ($client->invoices as $inv) {
            $timeline->push([
                'type'  => 'invoice',
                'date'  => $inv->issue_date ?? $inv->created_at,
                'title' => 'Invoice ' . $inv->invoice_number,
                'sub'   => 'Rs. ' . number_format($inv->total_amount, 2) . ' · ' . $inv->payment_status,
                'url'   => route('invoices.show', $inv),
                'color' => 'orange',
            ]);
        }
        foreach ($client->payments as $p) {
            $timeline->push([
                'type'  => 'payment',
                'date'  => $p->payment_date ?? $p->created_at,
                'title' => 'Payment received',
                'sub'   => 'Rs. ' . number_format($p->amount, 2) . ' · ' . $p->status,
                'url'   => route('payments.index'),
                'color' => 'green',
            ]);
        }

        // අලුත්ම ඉස්සරහට (descending)
        $timeline = $timeline->sortByDesc('date')->values();

        $clientStats = [
            'total_projects' => $totalProjects,
            'total_revenue'  => $totalRevenue,
            'due_amount'     => $dueAmount,
        ];

        return view('clients.show', compact('client', 'clientStats', 'timeline'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'nullable|email|max:255',
            'phone'       => 'nullable|string|max:20',
            'company'     => 'nullable|string|max:255',
            'type'        => 'nullable|string',
            'lead_source' => 'nullable|string|max:100',
            'address'     => 'nullable|string',
            'city'        => 'nullable|string|max:100',
            'website'     => 'nullable|string|max:255',
            'notes'       => 'nullable|string',
            'status'      => 'nullable|string',
        ]);

        $client->update($validated);

        ActivityLog::log('updated', 'Client', $client->id, $client->name,
            'Client updated: ' . $client->name, 'users', 'orange');

        return redirect()->route('clients.show', $client)
            ->with('success', 'Client updated successfully!');
    }

    public function destroy(Client $client)
    {
        $name = $client->name;
        $client->delete();

        ActivityLog::log('deleted', 'Client', null, $name,
            'Client deleted: ' . $name, 'users', 'red');

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully!');
    }
}