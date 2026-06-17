<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Client;
use App\Models\Quotation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::with('client')->latest()->get();

        $kanban = [
            'Inquiry'     => $jobs->where('status', 'Inquiry')->values(),
            'Confirmed'   => $jobs->where('status', 'Confirmed')->values(),
            'In Progress' => $jobs->where('status', 'In Progress')->values(),
            'Editing'     => $jobs->where('status', 'Editing')->values(),
            'Delivered'   => $jobs->where('status', 'Delivered')->values(),
            'Completed'   => $jobs->where('status', 'Completed')->values(),
        ];

        $stats = [
            'total'     => $jobs->count(),
            'active'    => $jobs->whereIn('status', ['Confirmed', 'In Progress', 'Editing'])->count(),
            'completed' => $jobs->where('status', 'Completed')->count(),
            'inquiry'   => $jobs->where('status', 'Inquiry')->count(),
        ];

        return view('jobs.index', compact('kanban', 'stats'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $quotations = Quotation::with('client')->where('status', 'Accepted')
            ->orderBy('created_at', 'desc')->get();
        $nextNumber = 'JOB-' . date('Y') . '-' . str_pad((Job::count() + 1), 4, '0', STR_PAD_LEFT);
        return view('jobs.create', compact('clients', 'quotations', 'nextNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_number'     => 'required|string|unique:jobs,job_number',
            'title'          => 'required|string|max:255',
            'client_id'      => 'required|exists:clients,id',
            'quotation_id'   => 'nullable|exists:quotations,id',
            'type'           => 'required|in:Wedding,Portrait,Commercial,Event,Product,Other',
            'event_date'     => 'nullable|date',
            'event_location' => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'status'         => 'required|in:Inquiry,Confirmed,In Progress,Editing,Delivered,Completed,Cancelled',
            'priority'       => 'required|in:Low,Medium,High',
            'budget'         => 'nullable|numeric|min:0',
            'delivery_date'  => 'nullable|date',
            'notes'          => 'nullable|string',
        ]);

        $job = Job::create($validated);

        ActivityLog::log('created', 'Job', $job->id, $job->job_number,
            'New job created: ' . $job->title . ' for ' . $job->client->name,
            'kanban', 'pink');

        return redirect()->route('jobs.index')
            ->with('success', 'Job created successfully!');
    }

    public function show(Job $job)
    {
        $job->load('client', 'quotation');
        return view('jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        $clients = Client::orderBy('name')->get();
        $quotations = Quotation::with('client')->orderBy('created_at', 'desc')->get();
        return view('jobs.edit', compact('job', 'clients', 'quotations'));
    }

    public function update(Request $request, Job $job)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'client_id'      => 'required|exists:clients,id',
            'quotation_id'   => 'nullable|exists:quotations,id',
            'type'           => 'required|in:Wedding,Portrait,Commercial,Event,Product,Other',
            'event_date'     => 'nullable|date',
            'event_location' => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'status'         => 'required|in:Inquiry,Confirmed,In Progress,Editing,Delivered,Completed,Cancelled',
            'priority'       => 'required|in:Low,Medium,High',
            'budget'         => 'nullable|numeric|min:0',
            'delivery_date'  => 'nullable|date',
            'notes'          => 'nullable|string',
        ]);

        $job->update($validated);

        ActivityLog::log('updated', 'Job', $job->id, $job->job_number,
            'Job updated: ' . $job->title, 'kanban', 'orange');

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Job updated successfully!');
    }

    public function updateStatus(Request $request, Job $job)
    {
        $request->validate([
            'status' => 'required|in:Inquiry,Confirmed,In Progress,Editing,Delivered,Completed,Cancelled',
        ]);

        $job->update(['status' => $request->status]);

        ActivityLog::log('updated', 'Job', $job->id, $job->job_number,
            'Job status changed to: ' . $request->status . ' - ' . $job->title,
            'kanban', 'orange');

        return back()->with('success', 'Job status updated!');
    }

    public function destroy(Job $job)
    {
        $title = $job->title;
        $number = $job->job_number;
        $job->delete();

        ActivityLog::log('deleted', 'Job', null, $number,
            'Job deleted: ' . $title, 'kanban', 'red');

        return redirect()->route('jobs.index')
            ->with('success', 'Job deleted successfully!');
    }
}