@extends('layouts.app')

@section('title', $client->name)

@section('content')

@php $currency = \App\Models\Setting::get('currency', 'Rs.'); @endphp

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
            <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $client->name }}</h1>
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
        <div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-primary/20 rounded-full flex items-center justify-center">
                    <span class="text-primary text-2xl font-bold">{{ $client->initials }}</span>
                </div>
                <div>
                    <h2 class="font-bold text-lg" :class="dark ? 'text-white' : 'text-gray-900'">{{ $client->name }}</h2>
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

            <div class="space-y-3 pt-4" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
                @if($client->phone)
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span class="text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $client->phone }}</span>
                </div>
                @endif

                @if($client->email)
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $client->email }}</span>
                </div>
                @endif

                @if($client->address || $client->city)
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ implode(', ', array_filter([$client->address, $client->city])) }}</span>
                </div>
                @endif

                @if($client->lead_source)
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">Source: {{ $client->lead_source }}</span>
                </div>
                @endif

                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">Since {{ $client->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        @if($client->notes)
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold text-sm mb-2" :class="dark ? 'text-white' : 'text-gray-900'">Notes</h3>
            <p class="text-gray-400 text-sm leading-relaxed">{{ $client->notes }}</p>
        </div>
        @endif

    </div>

    {{-- Right: Stats + Timeline --}}
    <div class="col-span-2 space-y-4">

        {{-- Quick Stats --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="rounded-xl p-4 text-center" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
                <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $clientStats['total_projects'] }}</p>
                <p class="text-gray-400 text-xs mt-1">Total Projects</p>
            </div>
            <div class="rounded-xl p-4 text-center" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
                <p class="text-2xl font-bold text-green-400">{{ $currency }} {{ number_format($clientStats['total_revenue']) }}</p>
                <p class="text-gray-400 text-xs mt-1">Total Revenue</p>
            </div>
            <div class="rounded-xl p-4 text-center" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
                <p class="text-2xl font-bold text-orange-400">{{ $currency }} {{ number_format($clientStats['due_amount']) }}</p>
                <p class="text-gray-400 text-xs mt-1">Due Amount</p>
            </div>
        </div>

        {{-- Activity Timeline --}}
        <div class="rounded-xl p-6" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Activity Timeline</h3>

            @if($timeline->isEmpty())
            <div class="text-center py-6">
                <svg class="w-8 h-8 text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <p class="text-gray-500 text-sm">No activity yet for this client</p>
                <p class="text-gray-600 text-xs mt-1">Quotations, jobs, invoices & payments will appear here</p>
            </div>
            @else
            <div class="space-y-1">
                @foreach($timeline as $item)
                <a href="{{ $item['url'] }}" class="flex items-start gap-3 p-2 rounded-lg transition-colors"
                   :class="dark ? 'hover:bg-dark-700' : 'hover:bg-gray-50'">
                    {{-- Icon --}}
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                        {{ $item['color'] === 'blue' ? 'bg-blue-500/20 text-blue-400' :
                           ($item['color'] === 'pink' ? 'bg-pink-500/20 text-pink-400' :
                           ($item['color'] === 'orange' ? 'bg-orange-500/20 text-orange-400' :
                           'bg-green-500/20 text-green-400')) }}">
                        @if($item['type'] === 'quotation')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        @elseif($item['type'] === 'job')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        @elseif($item['type'] === 'invoice')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                        @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate" :class="dark ? 'text-white' : 'text-gray-900'">{{ $item['title'] }}</p>
                        <p class="text-xs text-gray-500">{{ $item['sub'] }}</p>
                    </div>
                    {{-- Date --}}
                    <span class="text-xs text-gray-500 flex-shrink-0">
                        {{ \Illuminate\Support\Carbon::parse($item['date'])->format('d M Y') }}
                    </span>
                </a>
                @endforeach
            </div>
            @endif
        </div>

    </div>
</div>

{{-- Edit Client Modal --}}
<div id="editClientModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="rounded-xl w-full max-w-lg mx-4 max-h-screen overflow-y-auto" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="p-5 flex items-center justify-between" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
            <h2 class="font-semibold text-lg" :class="dark ? 'text-white' : 'text-gray-900'">Edit Client</h2>
            <button onclick="document.getElementById('editClientModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-red-400">
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
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Client Type *</label>
                    <select name="type" required
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        <option value="Personal" {{ $client->type === 'Personal' ? 'selected' : '' }}>Personal</option>
                        <option value="Corporate" {{ $client->type === 'Corporate' ? 'selected' : '' }}>Corporate</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Email</label>
                    <input type="email" name="email" value="{{ $client->email }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Phone *</label>
                    <input type="text" name="phone" value="{{ $client->phone }}" required
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Company Name</label>
                    <input type="text" name="company" value="{{ $client->company }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">City</label>
                    <input type="text" name="city" value="{{ $client->city }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
            </div>

            <div>
                <label class="text-gray-400 text-xs mb-1 block">Address</label>
                <input type="text" name="address" value="{{ $client->address }}"
                       class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                       :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Lead Source</label>
                    <select name="lead_source"
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        <option value="">— Select —</option>
                        @foreach(\App\Models\Client::$leadSources as $src)
                        <option value="{{ $src }}" {{ $client->lead_source === $src ? 'selected' : '' }}>{{ $src }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Status</label>
                    <select name="status"
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        <option value="Active" {{ $client->status === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Pending" {{ $client->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Inactive" {{ $client->status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-gray-400 text-xs mb-1 block">Notes</label>
                <textarea name="notes" rows="2"
                          class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                          :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">{{ $client->notes }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button"
                        onclick="document.getElementById('editClientModal').classList.add('hidden')"
                        class="flex-1 text-sm font-medium py-2 rounded-lg transition-colors"
                        :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
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