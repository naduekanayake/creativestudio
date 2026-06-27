@extends('layouts.app')

@section('title', 'Reminders')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Reminders</h1>
        <p class="text-gray-400 text-sm mt-0.5">Stay on top of important tasks & follow-ups</p>
    </div>
    <a href="{{ route('reminders.create') }}"
       class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Reminder
    </a>
</div>

{{-- Success Message --}}
@if(session('success'))
<div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- Stat Cards --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-purple-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['total'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-orange-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['pending'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Pending</p>
    </div>
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
        <div class="w-9 h-9 bg-green-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['done'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Done</p>
    </div>
</div>

{{-- Reminders Table --}}
<div class="rounded-xl" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
    <div class="p-4" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
        <h3 class="font-semibold" :class="dark ? 'text-white' : 'text-gray-900'">All Reminders</h3>
    </div>

    <div class="overflow-x-auto">
<table class="w-full min-w-[640px]">
        <thead>
            <tr class="text-gray-500 text-xs" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
                <th class="text-left px-4 py-3">TITLE</th>
                <th class="text-left px-4 py-3">CLIENT</th>
                <th class="text-left px-4 py-3">TYPE</th>
                <th class="text-left px-4 py-3">DATE</th>
                <th class="text-left px-4 py-3">PRIORITY</th>
                <th class="text-left px-4 py-3">STATUS</th>
                <th class="text-left px-4 py-3">ACTION</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reminders as $reminder)
            <tr :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #f3f4f6'"
                class="{{ $reminder->is_overdue ? 'bg-red-500/5' : '' }}">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 {{ $reminder->is_overdue ? 'bg-red-500/20' : 'bg-purple-500/20' }}">
                            <svg class="w-4 h-4 {{ $reminder->is_overdue ? 'text-red-400' : 'text-purple-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium {{ $reminder->is_overdue ? 'text-red-400' : '' }}"
                               :class="'{{ $reminder->is_overdue ? '1' : '0' }}' === '0' ? (dark ? 'text-white' : 'text-gray-900') : ''">
                                {{ $reminder->title }}
                                @if($reminder->is_overdue)
                                <span class="text-xs text-red-400 ml-1">(Overdue)</span>
                                @endif
                            </p>
                            @if($reminder->description)
                            <p class="text-gray-500 text-xs truncate max-w-48">{{ $reminder->description }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-sm text-gray-400">
                    {{ $reminder->client ? $reminder->client->name : '-' }}
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $reminder->type_color }}">
                        {{ $reminder->type }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm {{ $reminder->is_overdue ? 'text-red-400 font-medium' : 'text-gray-400' }}">
                    {{ $reminder->remind_date->format('d M Y') }}
                    @if($reminder->remind_time)
                    <span class="text-xs text-gray-500 block">{{ \Carbon\Carbon::parse($reminder->remind_time)->format('h:i A') }}</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $reminder->priority_color }}">
                        {{ $reminder->priority }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $reminder->status_color }}">
                        {{ $reminder->status }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-1">
                        {{-- Mark Done --}}
                        @if($reminder->status !== 'Done')
                        <form method="POST" action="{{ route('reminders.update-status', $reminder) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Done">
                            <button type="submit" class="text-gray-400 hover:text-green-400 transition-colors p-1" title="Mark Done">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                        </form>
                        @endif
                        {{-- Edit --}}
                        <a href="{{ route('reminders.edit', $reminder) }}"
                           class="text-gray-400 hover:text-primary transition-colors p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        {{-- Delete --}}
                        <form method="POST" action="{{ route('reminders.destroy', $reminder) }}"
                              onsubmit="return confirm('Delete this reminder?')" class="inline">
                            @csrf
                            @method('DELETE')
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
                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                    No reminders yet. Add your first reminder!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

    @if($reminders->hasPages())
    <div class="px-4 py-3" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
        {{ $reminders->links() }}
    </div>
    @endif
</div>

@endsection