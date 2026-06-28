@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@php
    $currency = \App\Models\Setting::get('currency', 'Rs.');
    $widgets = auth()->user()->dashboard_widgets;
    if (empty($widgets)) {
        $widgets = ['stats', 'analytics', 'recent_jobs', 'quick_stats', 'recent_activity'];
    }
    $show = fn($key) => in_array($key, $widgets);
@endphp

<div x-data="{ customize: false, widgets: @js($widgets) }">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Dashboard</h1>
            <p class="text-gray-400 text-sm mt-0.5">Welcome back, {{ auth()->user()->name }}!</p>
        </div>
        <button @click="customize = true"
                class="flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-lg transition-colors"
                :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Customize
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 text-sm">
        {{ session('success') }}
    </div>
    @endif

	@if(session('error'))
    <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-4 text-sm">
        {{ session('error') }}
    </div>
    @endif

    {{-- Backup (super_admin only) --}}
    @if(auth()->user()->isSuperAdmin())
    <div class="rounded-xl p-4 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
         :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-teal-500/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 4 3h8c2.5 0 4-1 4-3V7M4 7c0-2 1.5-3 4-3h8c2.5 0 4 1 4 3M4 7c0 2 1.5 3 4 3h8c2.5 0 4-1 4-3"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Data Backup</h3>
                <p class="text-xs" :class="dark ? 'text-gray-400' : 'text-gray-500'">Auto-backup runs daily. Create or download manually anytime.</p>
            </div>
        </div>
       <div class="flex items-center gap-2 flex-wrap">
            <form method="POST" action="{{ route('backup.run') }}" class="inline"
                  onsubmit="return confirm('Create a new backup now?')">
                @csrf
                <button type="submit"
                        class="text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2"
                        :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Backup
                </button>
            </form>
            <a href="{{ route('backup.download') }}"
               class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m6 5v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2"/>
                </svg>
                Download Backup
            </a>
        </div>
    </div>
    @endif

    {{-- Stats --}}
    @if($show('stats'))
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <div class="w-9 h-9 bg-blue-500/20 rounded-lg flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $analytics['total_clients'] }}</p>
            <p class="text-gray-400 text-xs mt-0.5">Total Clients</p>
        </div>
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <div class="w-9 h-9 bg-pink-500/20 rounded-lg flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $analytics['total_jobs'] }}</p>
            <p class="text-gray-400 text-xs mt-0.5">Total Jobs</p>
        </div>
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <div class="w-9 h-9 bg-green-500/20 rounded-lg flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-green-400">{{ $currency }} {{ number_format($analytics['total_revenue']) }}</p>
            <p class="text-gray-400 text-xs mt-0.5">Total Revenue</p>
        </div>
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <div class="w-9 h-9 bg-orange-500/20 rounded-lg flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-orange-400">{{ $analytics['pending_invoices'] }}</p>
            <p class="text-gray-400 text-xs mt-0.5">Pending Invoices</p>
        </div>
    </div>
    @endif

    {{-- Analytics --}}
    @if($show('analytics'))
    {{-- This Month Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <p class="text-gray-400 text-xs mb-1">THIS MONTH INCOME</p>
            <p class="text-xl font-bold text-green-400">{{ $currency }} {{ number_format($analytics['month_income']) }}</p>
        </div>
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <p class="text-gray-400 text-xs mb-1">THIS MONTH EXPENSE</p>
            <p class="text-xl font-bold text-red-400">{{ $currency }} {{ number_format($analytics['month_expense']) }}</p>
        </div>
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <p class="text-gray-400 text-xs mb-1">THIS MONTH PROFIT</p>
            <p class="text-xl font-bold {{ $analytics['month_profit'] >= 0 ? 'text-green-400' : 'text-red-400' }}">{{ $currency }} {{ number_format($analytics['month_profit']) }}</p>
        </div>
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <p class="text-gray-400 text-xs mb-1">PENDING PAYMENTS</p>
            <p class="text-xl font-bold text-orange-400">{{ $currency }} {{ number_format($analytics['pending_payments_total']) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
        {{-- Income vs Expense Chart --}}
        <div class="col-span-2 rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold text-sm mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Income vs Expense (Last 6 Months)</h3>
            <div id="incomeExpenseChart"></div>
        </div>

        {{-- Side metrics --}}
        <div class="space-y-4">
            {{-- Conversion Rate --}}
            <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
                <p class="text-gray-400 text-xs mb-1">QUOTATION CONVERSION</p>
                <p class="text-2xl font-bold text-primary">{{ $analytics['conversion_rate'] }}%</p>
                <p class="text-gray-500 text-xs mt-1">{{ $analytics['accepted_quotations'] }} of {{ $analytics['total_quotations'] }} accepted</p>
            </div>
            {{-- Overdue --}}
            <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
                <p class="text-gray-400 text-xs mb-1">OVERDUE INVOICES</p>
                <p class="text-2xl font-bold {{ $analytics['overdue_invoices'] > 0 ? 'text-red-400' : 'text-green-400' }}">{{ $analytics['overdue_invoices'] }}</p>
                <p class="text-gray-500 text-xs mt-1">Past due date</p>
            </div>
        </div>
    </div>

    {{-- Upcoming Events --}}
    <div class="rounded-xl mb-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="p-4 flex items-center justify-between" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
            <h3 class="font-semibold text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Upcoming Events (Next 30 Days)</h3>
            <a href="{{ route('calendar') }}" class="text-xs text-primary hover:underline">Calendar</a>
        </div>
        @forelse($analytics['upcoming_events'] as $event)
        <div class="px-4 py-3 flex items-center gap-3" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #f3f4f6'">
            <div class="w-10 h-10 bg-primary/20 rounded-lg flex flex-col items-center justify-center flex-shrink-0">
                <span class="text-xs font-bold text-primary leading-none">{{ $event->event_date->format('d') }}</span>
                <span class="text-[10px] text-primary leading-none mt-0.5">{{ $event->event_date->format('M') }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium truncate" :class="dark ? 'text-white' : 'text-gray-900'">{{ $event->title }}</p>
                <p class="text-xs text-gray-500">{{ $event->client->name ?? '-' }} · {{ $event->type ?? 'Event' }}</p>
            </div>
            <span class="px-2 py-0.5 rounded-full text-xs {{ $event->status_color }}">{{ $event->status }}</span>
        </div>
        @empty
        <div class="px-4 py-6 text-center text-gray-500 text-sm">No upcoming events in the next 30 days.</div>
        @endforelse
    </div>
    @endif

    {{-- Row 2 --}}
    @if($show('recent_jobs') || $show('quick_stats'))
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">

        {{-- Recent Jobs --}}
        @if($show('recent_jobs'))
        <div class="{{ $show('quick_stats') ? 'col-span-2' : 'col-span-3' }} rounded-xl" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <div class="p-4 flex items-center justify-between" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
                <h3 class="font-semibold text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Recent Jobs</h3>
                <a href="{{ route('jobs.index') }}" class="text-xs text-primary hover:underline">View All</a>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="text-gray-500 text-xs" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
                        <th class="text-left px-4 py-2">JOB</th>
                        <th class="text-left px-4 py-2">CLIENT</th>
                        <th class="text-left px-4 py-2">DATE</th>
                        <th class="text-left px-4 py-2">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\Job::with('client')->latest()->take(5)->get() as $job)
                    <tr :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #f3f4f6'">
                        <td class="px-4 py-2">
                            <p class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'">{{ Str::limit($job->title, 25) }}</p>
                            <p class="text-xs text-primary">{{ $job->job_number }}</p>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-400">{{ $job->client->name }}</td>
                        <td class="px-4 py-2 text-sm text-gray-400">
                            {{ $job->event_date ? $job->event_date->format('d M Y') : '-' }}
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $job->status_color }}">{{ $job->status }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Quick Stats --}}
        @if($show('quick_stats'))
        <div class="space-y-4 {{ $show('recent_jobs') ? '' : 'col-span-3 grid grid-cols-2 gap-4 space-y-0' }}">

            {{-- Pending Reminders --}}
            <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Reminders</h3>
                    <a href="{{ route('reminders.due') }}" class="text-xs text-primary hover:underline">View All</a>
                </div>
                @forelse(\App\Models\Reminder::where('status', 'Pending')->orderBy('remind_date')->take(3)->get() as $reminder)
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-2 h-2 rounded-full {{ $reminder->priority === 'High' ? 'bg-red-400' : ($reminder->priority === 'Medium' ? 'bg-orange-400' : 'bg-gray-400') }} flex-shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs truncate" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $reminder->title }}</p>
                        <p class="text-xs text-gray-500">{{ $reminder->remind_date->format('d M Y') }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-xs">No pending reminders.</p>
                @endforelse
            </div>

            {{-- Recent Payments --}}
            <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Recent Payments</h3>
                    <a href="{{ route('payments.index') }}" class="text-xs text-primary hover:underline">View All</a>
                </div>
                @forelse(\App\Models\Payment::with('client')->where('status', 'Completed')->latest()->take(3)->get() as $payment)
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <p class="text-xs" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $payment->client->name }}</p>
                        <p class="text-xs text-gray-500">{{ $payment->payment_date->format('d M Y') }}</p>
                    </div>
                    <p class="text-xs font-medium text-green-400">{{ $currency }} {{ number_format($payment->amount) }}</p>
                </div>
                @empty
                <p class="text-gray-500 text-xs">No payments yet.</p>
                @endforelse
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- Recent Activity --}}
    @if($show('recent_activity'))
    <div class="rounded-xl" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="p-4 flex items-center justify-between" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
            <h3 class="font-semibold text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Recent Activity</h3>
            <a href="{{ route('activity-log') }}" class="text-xs text-primary hover:underline">View All</a>
        </div>
        <div class="divide-y" :style="dark ? 'border-color:#252840' : 'border-color:#f3f4f6'">
            @forelse(\App\Models\ActivityLog::latest()->take(5)->get() as $log)
            <div class="px-4 py-3 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ $log->color_class }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $log->description }}</p>
                    <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full
                    {{ $log->action === 'created' ? 'bg-green-500/20 text-green-400' :
                       ($log->action === 'deleted' ? 'bg-red-500/20 text-red-400' :
                       'bg-orange-500/20 text-orange-400') }}">
                    {{ ucfirst($log->action) }}
                </span>
            </div>
            @empty
            <div class="px-4 py-6 text-center text-gray-500 text-sm">No activities yet.</div>
            @endforelse
        </div>
    </div>
    @endif

    {{-- Customize Modal --}}
    <div x-show="customize" x-cloak class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         @click.self="customize = false">
        <form method="POST" action="{{ route('dashboard.widgets') }}" class="rounded-xl w-full max-w-md"
              :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            @csrf
            @method('PATCH')

            <div class="p-4" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
                <h3 class="font-semibold" :class="dark ? 'text-white' : 'text-gray-900'">Customize Dashboard</h3>
                <p class="text-gray-400 text-xs mt-0.5">Choose which sections to show</p>
            </div>

            <div class="p-4 space-y-3">
                @php
                    $options = [
                        'stats'           => 'Stat Cards (Clients, Jobs, Revenue, Invoices)',
                        'analytics'       => 'Analytics (Income/Expense, Charts, Upcoming Events)',
                        'recent_jobs'     => 'Recent Jobs Table',
                        'quick_stats'     => 'Reminders & Recent Payments',
                        'recent_activity' => 'Recent Activity Feed',
                    ];
                @endphp
                @foreach($options as $key => $label)
                <label class="flex items-center gap-3 p-3 rounded-lg cursor-pointer transition-colors"
                       :style="dark ? 'background:#252840' : 'background:#f9fafb'">
                    <input type="checkbox" name="widgets[]" value="{{ $key }}"
                           x-model="widgets"
                           class="rounded" style="accent-color:#7C3AED"/>
                    <span class="text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $label }}</span>
                </label>
                @endforeach
            </div>

            <div class="p-4 flex gap-3" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
                <button type="button" @click="customize = false"
                        class="flex-1 text-sm font-medium py-2 rounded-lg transition-colors"
                        :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 bg-primary hover:bg-primary-hover text-white text-sm font-medium py-2 rounded-lg transition-colors">
                    Save Layout
                </button>
            </div>
        </form>
    </div>

</div>

{{-- ApexCharts --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.querySelector("#incomeExpenseChart");
    if (!el) return;

    const isDark = document.documentElement.classList.contains('dark');

    const options = {
        chart: {
            type: 'bar',
            height: 280,
            toolbar: { show: false },
            fontFamily: 'inherit',
        },
        series: [
            { name: 'Income', data: @json($analytics['chart_income']) },
            { name: 'Expense', data: @json($analytics['chart_expense']) },
        ],
        xaxis: {
            categories: @json($analytics['chart_labels']),
            labels: { style: { colors: isDark ? '#9ca3af' : '#6b7280' } },
        },
        yaxis: {
            labels: {
                style: { colors: isDark ? '#9ca3af' : '#6b7280' },
                formatter: (val) => 'Rs. ' + val.toLocaleString(),
            },
        },
        colors: ['#16a34a', '#dc2626'],
        plotOptions: {
            bar: { borderRadius: 4, columnWidth: '55%' },
        },
        dataLabels: { enabled: false },
        legend: { labels: { colors: isDark ? '#d1d5db' : '#374151' } },
        grid: { borderColor: isDark ? '#252840' : '#e5e7eb' },
        tooltip: {
            theme: isDark ? 'dark' : 'light',
            y: { formatter: (val) => 'Rs. ' + val.toLocaleString() },
        },
    };

    new ApexCharts(el, options).render();
});
</script>

@endsection
