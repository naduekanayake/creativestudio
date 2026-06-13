@extends('layouts.app')

@section('title', 'Clients')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Clients</h1>
        <p class="text-gray-400 text-sm mt-0.5">Manage your clients and their information</p>
    </div>
    <button onclick="document.getElementById('addClientModal').classList.remove('hidden')"
            class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Client
    </button>
</div>

{{-- Success Message --}}
@if(session('success'))
<div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- Stat Cards --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="w-9 h-9 bg-purple-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Clients</p>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="w-9 h-9 bg-green-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-white">{{ $stats['active'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Active Clients</p>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="w-9 h-9 bg-orange-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-white">{{ $stats['pending'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Pending Clients</p>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="w-9 h-9 bg-red-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-white">{{ $stats['inactive'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Inactive Clients</p>
    </div>
</div>

{{-- Clients Table --}}
<div class="bg-dark-800 border border-dark-700 rounded-xl">
    <div class="p-4 border-b border-dark-700 flex items-center justify-between">
        <h3 class="text-white font-semibold">All Clients</h3>
        <div class="flex items-center gap-2">
            <input type="text" placeholder="Search clients..."
                   class="bg-dark-700 text-gray-300 placeholder-gray-500 text-sm rounded-lg px-3 py-1.5 border border-dark-600 focus:outline-none focus:border-primary w-48"/>
        </div>
    </div>

    <table class="w-full">
        <thead>
            <tr class="text-gray-500 text-xs border-b border-dark-700">
                <th class="text-left px-4 py-3">CLIENT</th>
                <th class="text-left px-4 py-3">CONTACT</th>
                <th class="text-left px-4 py-3">TYPE</th>
                <th class="text-left px-4 py-3">STATUS</th>
                <th class="text-left px-4 py-3">JOINED</th>
                <th class="text-left px-4 py-3">ACTION</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-dark-700">
            @forelse($clients as $client)
            <tr class="hover:bg-dark-700/50 transition-colors">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-primary/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-primary text-sm font-bold">{{ $client->initials }}</span>
                        </div>
                        <div>
                            <p class="text-white text-sm font-medium">{{ $client->name }}</p>
                            @if($client->company)
                            <p class="text-gray-500 text-xs">{{ $client->company }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <p class="text-gray-300 text-sm">{{ $client->phone }}</p>
                    <p class="text-gray-500 text-xs">{{ $client->email }}</p>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs
                        {{ $client->type === 'Corporate' ? 'bg-blue-500/20 text-blue-400' : 'bg-purple-500/20 text-purple-400' }}">
                        {{ $client->type }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs
                        {{ $client->status === 'Active' ? 'bg-green-500/20 text-green-400' :
                           ($client->status === 'Pending' ? 'bg-orange-500/20 text-orange-400' :
                           'bg-red-500/20 text-red-400') }}">
                        {{ $client->status }}
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-400 text-sm">
                    {{ $client->created_at->format('d M Y') }}
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('clients.show', $client) }}"
                           class="text-gray-400 hover:text-white transition-colors p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('clients.destroy', $client) }}"
                              onsubmit="return confirm('Delete this client?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-400 transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                    No clients yet. Add your first client!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($clients->hasPages())
    <div class="px-4 py-3 border-t border-dark-700">
        {{ $clients->links() }}
    </div>
    @endif
</div>

{{-- Add Client Modal --}}
<div id="addClientModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-dark-800 border border-dark-700 rounded-xl w-full max-w-lg mx-4 max-h-screen overflow-y-auto">
        <div class="p-5 border-b border-dark-700 flex items-center justify-between">
            <h2 class="text-white font-semibold text-lg">Add New Client</h2>
            <button onclick="document.getElementById('addClientModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('clients.store') }}" class="p-5 space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Full Name *</label>
                    <input type="text" name="name" required
                           class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           placeholder="Enter full name"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Client Type *</label>
                    <select name="type" required
                            class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                        <option value="Personal">Personal</option>
                        <option value="Corporate">Corporate</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Email</label>
                    <input type="email" name="email"
                           class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           placeholder="email@example.com"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Phone *</label>
                    <input type="text" name="phone" required
                           class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           placeholder="+94 77 123 4567"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Company Name</label>
                    <input type="text" name="company"
                           class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           placeholder="Company (if any)"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">City</label>
                    <input type="text" name="city"
                           class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           placeholder="City"/>
                </div>
            </div>

            <div>
                <label class="text-gray-400 text-xs mb-1 block">Address</label>
                <input type="text" name="address"
                       class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                       placeholder="Full address"/>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Website</label>
                    <input type="text" name="website"
                           class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           placeholder="www.example.com"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Status</label>
                    <select name="status"
                            class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                        <option value="Active">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-gray-400 text-xs mb-1 block">Notes</label>
                <textarea name="notes" rows="2"
                          class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                          placeholder="Any notes about this client..."></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button"
                        onclick="document.getElementById('addClientModal').classList.add('hidden')"
                        class="flex-1 bg-dark-700 hover:bg-dark-600 text-white text-sm font-medium py-2 rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 bg-primary hover:bg-primary-hover text-white text-sm font-medium py-2 rounded-lg transition-colors">
                    Save Client
                </button>
            </div>
        </form>
    </div>
</div>

@endsection