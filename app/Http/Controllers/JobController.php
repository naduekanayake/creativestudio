<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Client;
use App\Models\Quotation;
use App\Models\Reminder;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            'type' => ['required', 'string', \Illuminate\Validation\Rule::in(\App\Models\Job::allTypes())],
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

        // Auto reminder — event date එකට link වෙච්ච reminder එකක්
        $this->createEventReminder($job);

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
            'type' => ['required', 'string', \Illuminate\Validation\Rule::in(\App\Models\Job::allTypes())],
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

        // Event date එක වෙනස් වුණත් — linked reminder එක update/delete වෙනවා
        $this->createEventReminder($job);

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
        // මේ job එකේ auto reminder එකත් අයින් කරනවා
        Reminder::where('source_type', 'job')->where('source_id', $job->id)->delete();

        $title = $job->title;
        $number = $job->job_number;
        $job->delete();

        ActivityLog::log('deleted', 'Job', null, $number,
            'Job deleted: ' . $title, 'kanban', 'red');

        return redirect()->route('jobs.index')
            ->with('success', 'Job deleted successfully!');
    }

    /**
     * Job event date එකම remind_date විදිහට auto reminder.
     * source_type/source_id වලින් link — date වෙනස් වුණොත් පරණ එක update වෙනවා (duplicate නැහැ).
     * Notification bell + Due page එක දවස් 3කට කලින්ම මේක පෙන්නනවා.
     */
    private function createEventReminder(Job $job): void
    {
        // මේ job එකේ පරණ auto reminder එක හොයාගන්නවා
        $existing = Reminder::where('source_type', 'job')
            ->where('source_id', $job->id)
            ->first();

        // Event date නැත්නම් හෝ අතීතයේ නම් — පරණ reminder එක තිබුණොත් අයින් කරනවා
        if (!$job->event_date || Carbon::parse($job->event_date)->lt(Carbon::today())) {
            if ($existing) {
                $existing->delete();
            }
            return;
        }

        $data = [
            'source_type' => 'job',
            'source_id'   => $job->id,
            'title'       => 'Shoot: ' . $job->title,
            'description' => 'Upcoming shoot/event for job ' . $job->job_number . '. Prepare gear and confirm details with client.',
            'client_id'   => $job->client_id,
            'type'        => 'Shoot',
            'remind_date' => Carbon::parse($job->event_date)->toDateString(),
            'remind_time' => null,
            'status'      => 'Pending',
            'priority'    => $job->priority ?? 'Medium',
        ];

        if ($existing) {
            $existing->update($data);   // පරණ එක update — duplicate නැහැ
        } else {
            Reminder::create($data);
        }
    }
}