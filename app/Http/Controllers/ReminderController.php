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