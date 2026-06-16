@extends('layouts.app')

@section('title', 'Deliverable')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('deliverables.index') }}" class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $deliverable->title }}</h1>
            <p class="text-gray-400 text-sm">Deliverable Details</p>
        </div>
        <span class="px-2 py-0.5 rounded-full text-xs {{ $deliverable->status_color }}">{{ $deliverable->status }}</span>
        <span class="px-2 py-0.5 rounded-full text-xs {{ $deliverable->type_color }}">{{ $deliverable->type }}</span>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('deliverables.edit', $deliverable) }}"
           class="text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2"
           :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        <form method="POST" action="{{ route('deliverables.destroy', $deliverable) }}"
              onsubmit="return confirm('Delete this deliverable?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2 bg-red-500/20 text-red-400 hover:bg-red-500/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete
            </button>
        </form>
    </div>
</div>

{{-- Success Message --}}
@if(session('success'))
<div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- Status Update Bar --}}
<div class="rounded-xl p-4 mb-4 flex items-center justify-between"
     :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
    <div class="flex items-center gap-3 flex-wrap">
        <span class="text-gray-400 text-sm">Update Status:</span>
        @foreach(['Pending', 'In Progress', 'Ready', 'Delivered', 'Approved'] as $statusOption)
        <form method="POST" action="{{ route('deliverables.update-status', $deliverable) }}" class="inline">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="{{ $statusOption }}">
            <button type="submit"
                    class="px-3 py-1 rounded-full text-xs font-medium transition-colors {{ $deliverable->status === $statusOption ? 'bg-primary text-white' : '' }}"
                    :class="'{{ $deliverable->status === $statusOption ? '1' : '0' }}' === '0' ? (dark ? 'bg-dark-700 text-gray-400 hover:bg-dark-600' : 'bg-gray-100 text-gray-500 hover:bg-gray-200') : ''">
                {{ $statusOption }}
            </button>
        </form>
        @endforeach
    </div>
    <p class="text-gray-400 text-xs">Added {{ $deliverable->created_at->format('d M Y') }}</p>
</div>

{{-- Content Grid --}}
<div class="grid grid-cols-3 gap-4">

    {{-- Left: Details --}}
    <div class="col-span-2 space-y-4">

        {{-- Main Info --}}
        <div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Deliverable Details</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-400 text-xs mb-1">CLIENT</p>
                    <a href="{{ route('clients.show', $deliverable->client) }}" class="text-primary text-sm hover:underline">
                        {{ $deliverable->client->name }}
                    </a>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-1">TYPE</p>
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $deliverable->type_color }}">{{ $deliverable->type }}</span>
                </div>
                @if($deliverable->delivery_method)
                <div>
                    <p class="text-gray-400 text-xs mb-1">DELIVERY METHOD</p>
                    <p class="text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $deliverable->delivery_method }}</p>
                </div>
                @endif
                @if($deliverable->due_date)
                <div>
                    <p class="text-gray-400 text-xs mb-1">DUE DATE</p>
                    <p class="text-sm font-medium {{ $deliverable->due_date->isPast() && !in_array($deliverable->status, ['Delivered', 'Approved']) ? 'text-red-400' : '' }}"
                       :class="'{{ $deliverable->due_date->isPast() && !in_array($deliverable->status, ['Delivered', 'Approved']) ? '1' : '0' }}' === '0' ? (dark ? 'text-white' : 'text-gray-900') : ''">
                        {{ $deliverable->due_date->format('d M Y') }}
                        @if($deliverable->due_date->isPast() && !in_array($deliverable->status, ['Delivered', 'Approved']))
                        <span class="text-xs text-red-400 ml-1">(Overdue)</span>
                        @endif
                    </p>
                </div>
                @endif
                @if($deliverable->delivered_date)
                <div>
                    <p class="text-gray-400 text-xs mb-1">DELIVERED DATE</p>
                    <p class="text-sm font-medium text-green-400">{{ $deliverable->delivered_date->format('d M Y') }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Drive Link --}}
        @if($deliverable->drive_link)
        <div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-3" :class="dark ? 'text-white' : 'text-gray-900'">Download Link</h3>
            <a href="{{ $deliverable->drive_link }}" target="_blank"
               class="flex items-center gap-2 text-primary hover:underline text-sm break-all">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                {{ $deliverable->drive_link }}
            </a>
        </div>
        @endif

        {{-- Notes --}}
        @if($deliverable->notes)
        <div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-3" :class="dark ? 'text-white' : 'text-gray-900'">Notes</h3>
            <p class="text-gray-400 text-sm leading-relaxed whitespace-pre-line">{{ $deliverable->notes }}</p>
        </div>
        @endif

    </div>

    {{-- Right: Info Panel --}}
    <div class="space-y-4">

        {{-- Client Card --}}
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-3 text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Client</h3>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold text-sm">{{ substr($deliverable->client->name, 0, 1) }}</span>
                </div>
                <div>
                    <p class="font-medium text-sm" :class="dark ? 'text-white' : 'text-gray-900'">{{ $deliverable->client->name }}</p>
                    @if($deliverable->client->phone)
                    <p class="text-gray-400 text-xs">{{ $deliverable->client->phone }}</p>
                    @endif
                </div>
            </div>
            <a href="{{ route('clients.show', $deliverable->client) }}"
               class="mt-3 block text-center text-xs text-primary hover:underline">
                View Client Profile →
            </a>
        </div>

        {{-- Linked Job --}}
        @if($deliverable->job)
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-3 text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Linked Job</h3>
            <p class="text-primary text-sm font-medium">{{ $deliverable->job->job_number }}</p>
            <p class="text-gray-400 text-xs mt-1">{{ $deliverable->job->title }}</p>
            <span class="px-2 py-0.5 rounded-full text-xs {{ $deliverable->job->status_color }} mt-2 inline-block">
                {{ $deliverable->job->status }}
            </span>
            <a href="{{ route('jobs.show', $deliverable->job) }}"
               class="mt-3 block text-center text-xs text-primary hover:underline">
                View Job →
            </a>
        </div>
        @endif

        {{-- Status Summary --}}
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-3 text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Status</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Current Status</span>
                    <span class="px-2 py-0.5 rounded-full {{ $deliverable->status_color }}">{{ $deliverable->status }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Type</span>
                    <span class="px-2 py-0.5 rounded-full {{ $deliverable->type_color }}">{{ $deliverable->type }}</span>
                </div>
                @if($deliverable->delivery_method)
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Method</span>
                    <span :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $deliverable->delivery_method }}</span>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection