<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(10);
        $stats = [
            'total' => Client::count(),
            'active' => Client::where('status', 'Active')->count(),
            'inactive' => Client::where('status', 'Inactive')->count(),
            'pending' => Client::where('status', 'Pending')->count(),
        ];
        return view('clients.index', compact('clients', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'type'    => 'required|in:Personal,Corporate',
            'address' => 'nullable|string|max:500',
            'city'    => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'notes'   => 'nullable|string',
            'status'  => 'required|in:Active,Inactive,Pending',
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client added successfully!');
    }

    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'type'    => 'required|in:Personal,Corporate',
            'address' => 'nullable|string|max:500',
            'city'    => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'notes'   => 'nullable|string',
            'status'  => 'required|in:Active,Inactive,Pending',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client updated successfully!');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully!');
    }
}