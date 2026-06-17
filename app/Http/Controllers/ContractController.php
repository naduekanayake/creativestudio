<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Client;
use App\Models\Job;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::with('client')->latest()->paginate(15);
        $stats = [
            'total'     => Contract::count(),
            'signed'    => Contract::where('status', 'Signed')->count(),
            'draft'     => Contract::where('status', 'Draft')->count(),
            'value'     => Contract::whereIn('status', ['Signed', 'Completed'])->sum('total_amount'),
        ];
        return view('contracts.index', compact('contracts', 'stats'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $jobs = Job::with('client')->latest()->get();
        $nextNumber = 'CON-' . date('Y') . '-' . str_pad((Contract::count() + 1), 4, '0', STR_PAD_LEFT);
        return view('contracts.create', compact('clients', 'jobs', 'nextNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_number' => 'required|string|unique:contracts,contract_number',
            'client_id'       => 'required|exists:clients,id',
            'job_id'          => 'nullable|exists:jobs,id',
            'title'           => 'required|string|max:255',
            'type'            => 'required|in:Wedding,Event,Commercial,Portrait,Other',
            'event_date'      => 'nullable|date',
            'event_location'  => 'nullable|string|max:255',
            'total_amount'    => 'required|numeric|min:0',
            'advance_amount'  => 'nullable|numeric|min:0',
            'contract_date'   => 'required|date',
            'terms'           => 'nullable|string',
            'notes'           => 'nullable|string',
            'status'          => 'required|in:Draft,Sent,Signed,Completed,Cancelled',
        ]);

        $contract = Contract::create($validated);

        ActivityLog::log('created', 'Contract', $contract->id, $contract->contract_number,
            'Contract created: ' . $contract->title . ' for ' . $contract->client->name,
            'file-text', 'blue');

        return redirect()->route('contracts.index')
            ->with('success', 'Contract created successfully!');
    }

    public function show(Contract $contract)
    {
        $contract->load('client', 'job');
        return view('contracts.show', compact('contract'));
    }

    public function edit(Contract $contract)
    {
        $clients = Client::orderBy('name')->get();
        $jobs = Job::with('client')->latest()->get();
        return view('contracts.edit', compact('contract', 'clients', 'jobs'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'client_id'       => 'required|exists:clients,id',
            'job_id'          => 'nullable|exists:jobs,id',
            'title'           => 'required|string|max:255',
            'type'            => 'required|in:Wedding,Event,Commercial,Portrait,Other',
            'event_date'      => 'nullable|date',
            'event_location'  => 'nullable|string|max:255',
            'total_amount'    => 'required|numeric|min:0',
            'advance_amount'  => 'nullable|numeric|min:0',
            'contract_date'   => 'required|date',
            'terms'           => 'nullable|string',
            'notes'           => 'nullable|string',
            'status'          => 'required|in:Draft,Sent,Signed,Completed,Cancelled',
        ]);

        $contract->update($validated);

        ActivityLog::log('updated', 'Contract', $contract->id, $contract->contract_number,
            'Contract updated: ' . $contract->title, 'file-text', 'orange');

        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Contract updated successfully!');
    }

    public function updateStatus(Request $request, Contract $contract)
    {
        $request->validate([
            'status' => 'required|in:Draft,Sent,Signed,Completed,Cancelled',
        ]);
        $contract->update(['status' => $request->status]);

        ActivityLog::log('updated', 'Contract', $contract->id, $contract->contract_number,
            'Contract status changed to: ' . $request->status, 'file-text', 'orange');

        return back()->with('success', 'Contract status updated!');
    }

    public function destroy(Contract $contract)
    {
        $number = $contract->contract_number;
        $contract->delete();

        ActivityLog::log('deleted', 'Contract', null, $number,
            'Contract deleted: ' . $number, 'file-text', 'red');

        return redirect()->route('contracts.index')
            ->with('success', 'Contract deleted successfully!');
    }
}