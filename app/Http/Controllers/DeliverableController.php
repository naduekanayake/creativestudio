<?php

namespace App\Http\Controllers;

use App\Models\Deliverable;
use App\Models\Client;
use App\Models\Job;
use Illuminate\Http\Request;

class DeliverableController extends Controller
{
    public function index()
    {
        $deliverables = Deliverable::with('client', 'job')->latest()->paginate(15);

        $stats = [
            'total'       => Deliverable::count(),
            'pending'     => Deliverable::where('status', 'Pending')->count(),
            'ready'       => Deliverable::where('status', 'Ready')->count(),
            'delivered'   => Deliverable::where('status', 'Delivered')->count(),
        ];

        return view('deliverables.index', compact('deliverables', 'stats'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $jobs = Job::with('client')->orderBy('created_at', 'desc')->get();
        return view('deliverables.create', compact('clients', 'jobs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'client_id'       => 'required|exists:clients,id',
            'job_id'          => 'nullable|exists:jobs,id',
            'type'            => 'required|in:Photos,Videos,Album,Raw Files,Edited Files,Prints,Other',
            'quantity'        => 'nullable|integer|min:1',
            'delivery_method' => 'nullable|string|max:255',
            'due_date'        => 'nullable|date',
            'delivered_date'  => 'nullable|date',
            'drive_link'      => 'nullable|string|max:500',
            'notes'           => 'nullable|string',
            'status'          => 'required|in:Pending,In Progress,Ready,Delivered,Approved',
        ]);

        Deliverable::create($validated);

        return redirect()->route('deliverables.index')
            ->with('success', 'Deliverable created successfully!');
    }

    public function show(Deliverable $deliverable)
    {
        $deliverable->load('client', 'job');
        return view('deliverables.show', compact('deliverable'));
    }

    public function edit(Deliverable $deliverable)
    {
        $clients = Client::orderBy('name')->get();
        $jobs = Job::with('client')->orderBy('created_at', 'desc')->get();
        return view('deliverables.edit', compact('deliverable', 'clients', 'jobs'));
    }

    public function update(Request $request, Deliverable $deliverable)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'client_id'       => 'required|exists:clients,id',
            'job_id'          => 'nullable|exists:jobs,id',
            'type'            => 'required|in:Photos,Videos,Album,Raw Files,Edited Files,Prints,Other',
            'quantity'        => 'nullable|integer|min:1',
            'delivery_method' => 'nullable|string|max:255',
            'due_date'        => 'nullable|date',
            'delivered_date'  => 'nullable|date',
            'drive_link'      => 'nullable|string|max:500',
            'notes'           => 'nullable|string',
            'status'          => 'required|in:Pending,In Progress,Ready,Delivered,Approved',
        ]);

        $deliverable->update($validated);

        return redirect()->route('deliverables.show', $deliverable)
            ->with('success', 'Deliverable updated successfully!');
    }

    public function updateStatus(Request $request, Deliverable $deliverable)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Ready,Delivered,Approved',
        ]);

        $data = ['status' => $request->status];
        if ($request->status === 'Delivered' && !$deliverable->delivered_date) {
            $data['delivered_date'] = now()->toDateString();
        }

        $deliverable->update($data);
        return back()->with('success', 'Status updated!');
    }

    public function destroy(Deliverable $deliverable)
    {
        $deliverable->delete();
        return redirect()->route('deliverables.index')
            ->with('success', 'Deliverable deleted successfully!');
    }
}