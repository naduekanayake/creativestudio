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
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'type'    => 'nullable|string',
            'address' => 'nullable|string',
            'city'    => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'notes'   => 'nullable|string',
            'status'  => 'nullable|string',
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
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'type'    => 'nullable|string',
            'address' => 'nullable|string',
            'city'    => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'notes'   => 'nullable|string',
            'status'  => 'nullable|string',
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