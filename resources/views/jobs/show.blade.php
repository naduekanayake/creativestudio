@extends('layouts.app')

@section('title', 'Job ' . $job->job_number)

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('jobs.index') }}" class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $job->title }}</h1>
            <p class="text-gray-400 text-sm">{{ $job->job_number }}</p>
        </div>
        <span class="px-2 py-0.5 rounded-full text-xs {{ $job->status_color }}">{{ $job->status }}</span>
        <span class="px-2 py-0.5 rounded-full text-xs {{ $job->type_color }}">{{ $job->type }}</span>
        <span class="px-2 py-0.5 rounded-full text-xs {{ $job->priority_color }}">{{ $job->priority }}</span>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('jobs.edit', $job) }}"
           class="text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2"
           :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        <form method="POST" action="{{ route('jobs.destroy', $job) }}"
              onsubmit="return confirm('Delete this job?')">
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
        <span class="text-gray-400 text-sm">Move to:</span>
        @foreach(['Inquiry', 'Confirmed', 'In Progress', 'Editing', 'Delivered', 'Completed', 'Cancelled'] as $statusOption)
        <form method="POST" action="{{ route('jobs.update-status', $job) }}" class="inline">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="{{ $statusOption }}">
            <button type="submit"
                    class="px-3 py-1 rounded-full text-xs font-medium transition-colors {{ $job->status === $statusOption ? 'bg-primary text-white' : '' }}"
                    :class="'{{ $job->status === $statusOption ? '1' : '0' }}' === '0' ? (dark ? 'bg-dark-700 text-gray-400 hover:bg-dark-600' : 'bg-gray-100 text-gray-500 hover:bg-gray-200') : ''">
                {{ $statusOption }}
            </button>
        </form>
        @endforeach
    </div>
    <p class="text-gray-400 text-xs">Created {{ $job->created_at->format('d M Y') }}</p>
</div>

{{-- Content Grid --}}
<div class="grid grid-cols-3 gap-4">

    {{-- Left: Job Details --}}
    <div class="col-span-2 space-y-4">

        {{-- Main Info --}}
        <div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Job Details</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-400 text-xs mb-1">CLIENT</p>
                    <a href="{{ route('clients.show', $job->client) }}" class="text-primary text-sm hover:underline">
                        {{ $job->client->name }}
                    </a>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-1">JOB TYPE</p>
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $job->type_color }}">{{ $job->type }}</span>
                </div>
                @if($job->event_date)
                <div>
                    <p class="text-gray-400 text-xs mb-1">EVENT DATE</p>
                    <p class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'">{{ $job->event_date->format('d M Y') }}</p>
                </div>
                @endif
                @if($job->event_location)
                <div>
                    <p class="text-gray-400 text-xs mb-1">EVENT LOCATION</p>
                    <p class="text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $job->event_location }}</p>
                </div>
                @endif
                @if($job->delivery_date)
                <div>
                    <p class="text-gray-400 text-xs mb-1">DELIVERY DATE</p>
                    <p class="text-sm font-medium {{ $job->delivery_date->isPast() && !in_array($job->status, ['Completed', 'Delivered']) ? 'text-red-400' : '' }}"
                       :class="'{{ $job->delivery_date->isPast() && !in_array($job->status, ['Completed', 'Delivered']) ? '1' : '0' }}' === '0' ? (dark ? 'text-white' : 'text-gray-900') : ''">
                        {{ $job->delivery_date->format('d M Y') }}
                        @if($job->delivery_date->isPast() && !in_array($job->status, ['Completed', 'Delivered']))
                        <span class="text-xs text-red-400 ml-1">(Overdue)</span>
                        @endif
                    </p>
                </div>
                @endif
                @if($job->budget)
                <div>
                    <p class="text-gray-400 text-xs mb-1">BUDGET</p>
                    <p class="text-sm font-medium text-green-400">Rs. {{ number_format($job->budget, 2) }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Description --}}
        @if($job->description)
        <div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-3" :class="dark ? 'text-white' : 'text-gray-900'">Description</h3>
            <p class="text-gray-400 text-sm leading-relaxed">{{ $job->description }}</p>
        </div>
        @endif

        {{-- Notes --}}
        @if($job->notes)
        <div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-3" :class="dark ? 'text-white' : 'text-gray-900'">Internal Notes</h3>
            <p class="text-gray-400 text-sm leading-relaxed whitespace-pre-line">{{ $job->notes }}</p>
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
                    <span class="text-white font-bold text-sm">{{ substr($job->client->name, 0, 1) }}</span>
                </div>
                <div>
                    <p class="font-medium text-sm" :class="dark ? 'text-white' : 'text-gray-900'">{{ $job->client->name }}</p>
                    @if($job->client->phone)
                    <p class="text-gray-400 text-xs">{{ $job->client->phone }}</p>
                    @endif
                    @if($job->client->email)
                    <p class="text-gray-400 text-xs">{{ $job->client->email }}</p>
                    @endif
                </div>
            </div>
            <a href="{{ route('clients.show', $job->client) }}"
               class="mt-3 block text-center text-xs text-primary hover:underline">
                View Client Profile →
            </a>
        </div>

        {{-- Linked Quotation --}}
        @if($job->quotation)
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-3 text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Linked Quotation</h3>
            <p class="text-primary text-sm font-medium">{{ $job->quotation->quotation_number }}</p>
            <p class="text-gray-400 text-xs mt-1">Rs. {{ number_format($job->quotation->total_amount, 2) }}</p>
            <span class="px-2 py-0.5 rounded-full text-xs {{ $job->quotation->status_color }} mt-2 inline-block">
                {{ $job->quotation->status }}
            </span>
            <a href="{{ route('quotations.show', $job->quotation) }}"
               class="mt-3 block text-center text-xs text-primary hover:underline">
                View Quotation →
            </a>
        </div>
        @endif

        {{-- Job Info Summary --}}
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-3 text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Job Info</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Status</span>
                    <span class="px-2 py-0.5 rounded-full {{ $job->status_color }}">{{ $job->status }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Priority</span>
                    <span class="px-2 py-0.5 rounded-full {{ $job->priority_color }}">{{ $job->priority }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Type</span>
                    <span class="px-2 py-0.5 rounded-full {{ $job->type_color }}">{{ $job->type }}</span>
                </div>
                @if($job->budget)
                <div class="flex justify-between text-xs pt-2" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
                    <span class="text-gray-400">Budget</span>
                    <span class="text-green-400 font-medium">Rs. {{ number_format($job->budget, 2) }}</span>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection