@extends('layouts.app')

@section('title', $client->name)

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('clients.index') }}"
           class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">{{ $client->name }}</h1>
            <p class="text-gray-400 text-sm">Client Profile</p>
        </div>
    </div>
    <button onclick="document.getElementById('editClientModal').classList.remove('hidden')"
            class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Edit Client
    </button>
</div>

{{-- Success Message --}}
@if(session('success'))
<div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- Profile Card --}}
<div class="grid grid-cols-3 gap-4">

    {{-- Left: Client Info --}}
    <div class="space-y-4">

        {{-- Avatar + Basic Info --}}
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-5">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-primary/20 rounded-full flex items-center justify-center">
                    <span class="text-primary text-2xl font-bold">{{ $client->initials }}</span>
                </div>
                <div>
                    <h2 class="text-white font-bold text-lg">{{ $client->name }}</h2>
                    @if($client->company)
                    <p class="text-gray-400 text-sm">{{ $client->company }}</p>
                    @endif
                    <div class="flex items-center gap-2 mt-1">
                        <span class="px-2 py-0.5 rounded-full text-xs
                            {{ $client->status === 'Active' ? 'bg-green-500/20 text-green-400' :
                               ($client->status === 'Pending' ? 'bg-orange-500/20 text-orange-400' :
                               'bg-red-500/20 text-red-400') }}">
                            {{ $client->status }}
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-xs
                            {{ $client->type === 'Corporate' ? 'bg-blue-500/20 text-blue-400' : 'bg-purple-500/20 text-purple-400' }}">
                            {{ $client->type }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="space-y-3 border-t border-dark-700 pt-4">
                @if($client->phone)
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span class="text-gray-300 text-sm">{{ $client->phone }}</span>
                </div>
                @endif

                @if($client->email)
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-gray-300 text-sm">{{ $client->email }}</span>
                </div>
                @endif

                @if($client->address || $client->city)
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-gray-300 text-sm">{{ implode(', ', array_filter([$client->address, $client->city])) }}</span>
                </div>
                @endif

                @if($client->website)
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                    <span class="text-gray-300 text-sm">{{ $client->website }}</span>
                </div>
                @endif

                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-gray-300 text-sm">Since {{ $client->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        @if($client->notes)
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
            <h3 class="text-white font-semibold text-sm mb-2">Notes</h3>
            <p class="text-gray-400 text-sm leading-relaxed">{{ $client->notes }}</p>
        </div>
        @endif

    </div>

    {{-- Right: Stats + Activity --}}
    <div class="col-span-2 space-y-4">

        {{-- Quick Stats --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-white">0</p>
                <p class="text-gray-400 text-xs mt-1">Total Projects</p>
            </div>
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-green-400">Rs. 0</p>
                <p class="text-gray-400 text-xs mt-1">Total Revenue</p>
            </div>
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-orange-400">Rs. 0</p>
                <p class="text-gray-400 text-xs mt-1">Due Amount</p>
            </div>
        </div>

        {{-- Empty State for Projects --}}
<div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
    <h3 class="text-white font-semibold mb-4">Recent Projects</h3>
    <div class="text-center py-6">
        <svg class="w-8 h-8 text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        <p class="text-gray-500 text-sm">No projects yet for this client</p>
        <p class="text-gray-600 text-xs mt-1">Projects will appear here once added</p>
    </div>
</div>

    </div>
</div>

{{-- Edit Client Modal --}}
<div id="editClientModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-dark-800 border border-dark-700 rounded-xl w-full max-w-lg mx-4 max-h-screen overflow-y-auto">
        <div class="p-5 border-b border-dark-700 flex items-center justify-between">
            <h2 class="text-white font-semibold text-lg">Edit Client</h2>
            <button onclick="document.getElementById('editClientModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('clients.update', $client) }}" class="p-5 space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Full Name *</label>
                    <input type="text" name="name" value="{{ $client->name }}" required
                           class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Client Type *</label>
                    <select name="type" required
                            class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                        <option value="Personal" {{ $client->type === 'Personal' ? 'selected' : '' }}>Personal</option>
                        <option value="Corporate" {{ $client->type === 'Corporate' ? 'selected' : '' }}>Corporate</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Email</label>
                    <input type="email" name="email" value="{{ $client->email }}"
                           class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Phone *</label>
                    <input type="text" name="phone" value="{{ $client->phone }}" required
                           class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Company Name</label>
                    <input type="text" name="company" value="{{ $client->company }}"
                           class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">City</label>
                    <input type="text" name="city" value="{{ $client->city }}"
                           class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"/>
                </div>
            </div>

            <div>
                <label class="text-gray-400 text-xs mb-1 block">Address</label>
                <input type="text" name="address" value="{{ $client->address }}"
                       class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"/>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Website</label>
                    <input type="text" name="website" value="{{ $client->website }}"
                           class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Status</label>
                    <select name="status"
                            class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                        <option value="Active" {{ $client->status === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Pending" {{ $client->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Inactive" {{ $client->status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-gray-400 text-xs mb-1 block">Notes</label>
                <textarea name="notes" rows="2"
                          class="w-full bg-dark-700 border border-dark-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary">{{ $client->notes }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button"
                        onclick="document.getElementById('editClientModal').classList.add('hidden')"
                        class="flex-1 bg-dark-700 hover:bg-dark-600 text-white text-sm font-medium py-2 rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 bg-primary hover:bg-primary-hover text-white text-sm font-medium py-2 rounded-lg transition-colors">
                    Update Client
                </button>
            </div>
        </form>
    </div>
</div>

@endsection