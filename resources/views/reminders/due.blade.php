@extends('layouts.app')

@section('title', 'Due Reminders')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Due Reminders</h1>
        <p class="text-gray-400 text-sm mt-0.5">Send reminders to clients via WhatsApp</p>
    </div>
    <a href="{{ route('reminders.index') }}"
       class="text-sm font-medium px-4 py-2 rounded-lg transition-colors"
       :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
        All Reminders
    </a>
</div>

@if(session('success'))
<div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- Stat Cards --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-red-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-red-400">{{ $stats['overdue'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Overdue</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-orange-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-orange-400">{{ $stats['today'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Due Today</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-blue-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-blue-400">{{ $stats['upcoming'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Upcoming (7 days)</p>
    </div>
</div>

{{-- Overdue Section --}}
@if($overdue->count() > 0)
<div class="mb-6">
    <h3 class="font-semibold mb-3 flex items-center gap-2" :class="dark ? 'text-white' : 'text-gray-900'">
        <span class="w-2 h-2 rounded-full bg-red-500"></span>
        Overdue ({{ $overdue->count() }})
    </h3>
    <div class="space-y-2">
        @foreach($overdue as $reminder)
            @include('reminders.partials.due-card', ['reminder' => $reminder, 'accent' => 'red'])
        @endforeach
    </div>
</div>
@endif

{{-- Due Today Section --}}
@if($dueToday->count() > 0)
<div class="mb-6">
    <h3 class="font-semibold mb-3 flex items-center gap-2" :class="dark ? 'text-white' : 'text-gray-900'">
        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
        Due Today ({{ $dueToday->count() }})
    </h3>
    <div class="space-y-2">
        @foreach($dueToday as $reminder)
            @include('reminders.partials.due-card', ['reminder' => $reminder, 'accent' => 'orange'])
        @endforeach
    </div>
</div>
@endif

{{-- Upcoming Section --}}
@if($upcoming->count() > 0)
<div class="mb-6">
    <h3 class="font-semibold mb-3 flex items-center gap-2" :class="dark ? 'text-white' : 'text-gray-900'">
        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
        Upcoming ({{ $upcoming->count() }})
    </h3>
    <div class="space-y-2">
        @foreach($upcoming as $reminder)
            @include('reminders.partials.due-card', ['reminder' => $reminder, 'accent' => 'blue'])
        @endforeach
    </div>
</div>
@endif

{{-- Empty state --}}
@if($overdue->count() === 0 && $dueToday->count() === 0 && $upcoming->count() === 0)
<div class="rounded-xl p-12 text-center" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
    <div class="w-14 h-14 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
        <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <p class="font-medium" :class="dark ? 'text-white' : 'text-gray-900'">All caught up!</p>
    <p class="text-gray-400 text-sm mt-1">No reminders due in the next 7 days.</p>
</div>
@endif

@endsection