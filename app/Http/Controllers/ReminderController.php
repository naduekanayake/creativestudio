<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\Client;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index()
    {
        $reminders = Reminder::with('client')
            ->orderBy('remind_date', 'asc')
            ->paginate(15);

        $stats = [
            'total'   => Reminder::count(),
            'pending' => Reminder::where('status', 'Pending')->count(),
            'overdue' => Reminder::where('status', 'Pending')
                ->where('remind_date', '<', today())
                ->count(),
            'done'    => Reminder::where('status', 'Done')->count(),
        ];

        return view('reminders.index', compact('reminders', 'stats'));
    }

    public function due()
    {
        $today = today();

        // Overdue (past + pending)
        $overdue = Reminder::with('client')
            ->where('status', 'Pending')
            ->where('remind_date', '<', $today)
            ->orderBy('remind_date', 'asc')
            ->get();

        // Due today
        $dueToday = Reminder::with('client')
            ->where('status', 'Pending')
            ->whereDate('remind_date', $today)
            ->orderBy('remind_time', 'asc')
            ->get();

        // Upcoming (next 7 days)
        $upcoming = Reminder::with('client')
            ->where('status', 'Pending')
            ->whereBetween('remind_date', [$today->copy()->addDay(), $today->copy()->addDays(7)])
            ->orderBy('remind_date', 'asc')
            ->get();

        $stats = [
            'overdue'  => $overdue->count(),
            'today'    => $dueToday->count(),
            'upcoming' => $upcoming->count(),
        ];

        return view('reminders.due', compact('overdue', 'dueToday', 'upcoming', 'stats'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        return view('reminders.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id'   => 'nullable|exists:clients,id',
            'type'        => 'required|in:Payment,Delivery,Shoot,Follow Up,Other',
            'remind_date' => 'required|date',
            'remind_time' => 'nullable',
            'status'      => 'required|in:Pending,Done,Snoozed',
            'priority'    => 'required|in:Low,Medium,High',
        ]);

        Reminder::create($validated);

        return redirect()->route('reminders.index')
            ->with('success', 'Reminder created successfully!');
    }

    public function edit(Reminder $reminder)
    {
        $clients = Client::orderBy('name')->get();
        return view('reminders.edit', compact('reminder', 'clients'));
    }

    public function update(Request $request, Reminder $reminder)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id'   => 'nullable|exists:clients,id',
            'type'        => 'required|in:Payment,Delivery,Shoot,Follow Up,Other',
            'remind_date' => 'required|date',
            'remind_time' => 'nullable',
            'status'      => 'required|in:Pending,Done,Snoozed',
            'priority'    => 'required|in:Low,Medium,High',
        ]);

        $reminder->update($validated);

        return redirect()->route('reminders.index')
            ->with('success', 'Reminder updated successfully!');
    }

    public function updateStatus(Request $request, Reminder $reminder)
    {
        $request->validate([
            'status' => 'required|in:Pending,Done,Snoozed',
        ]);
        $reminder->update(['status' => $request->status]);
        return back()->with('success', 'Reminder status updated!');
    }

    public function destroy(Reminder $reminder)
    {
        $reminder->delete();
        return redirect()->route('reminders.index')
            ->with('success', 'Reminder deleted!');
    }
}