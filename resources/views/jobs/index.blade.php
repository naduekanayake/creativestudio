@extends('layouts.app')

@section('title', 'Job Management')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Job Management</h1>
        <p class="text-gray-400 text-sm mt-0.5">Track all photography & videography jobs</p>
    </div>
    <a href="{{ route('jobs.create') }}"
       class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Job
    </a>
</div>

{{-- Success Message --}}
@if(session('success'))
<div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-purple-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['total'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Jobs</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-yellow-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['active'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Active Jobs</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-green-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['completed'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Completed</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-gray-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['inquiry'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Inquiries</p>
    </div>
</div>

{{-- Kanban Board --}}
<div class="flex gap-3 overflow-x-auto pb-4">

    @php
    $columns = [
        'Inquiry'     => ['color' => 'text-gray-400',  'bg' => 'bg-gray-500/20',   'border' => '#374151'],
        'Confirmed'   => ['color' => 'text-blue-400',  'bg' => 'bg-blue-500/20',   'border' => '#1d4ed8'],
        'In Progress' => ['color' => 'text-yellow-400','bg' => 'bg-yellow-500/20', 'border' => '#d97706'],
        'Editing'     => ['color' => 'text-purple-400','bg' => 'bg-purple-500/20', 'border' => '#7c3aed'],
        'Delivered'   => ['color' => 'text-teal-400',  'bg' => 'bg-teal-500/20',   'border' => '#0d9488'],
        'Completed'   => ['color' => 'text-green-400', 'bg' => 'bg-green-500/20',  'border' => '#16a34a'],
    ];
    @endphp

    @foreach($columns as $status => $style)
   <div class="w-64 flex-shrink-0">
        {{-- Column Header --}}
        <div class="flex items-center justify-between mb-3 px-1">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold {{ $style['color'] }}">{{ strtoupper($status) }}</span>
                <span class="text-xs px-1.5 py-0.5 rounded-full {{ $style['bg'] }} {{ $style['color'] }}">
                    {{ $kanban[$status]->count() }}
                </span>
            </div>
        </div>

        {{-- Cards --}}
        <div class="space-y-2 min-h-32">
            @forelse($kanban[$status] as $job)
            <div class="rounded-xl p-3 transition-transform"
                 :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-1 mb-1">
                    <a href="{{ route('jobs.show', $job) }}"
                       class="text-gray-400 hover:text-primary p-1 rounded transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </a>
                    <a href="{{ route('jobs.edit', $job) }}"
                       class="text-gray-400 hover:text-primary p-1 rounded transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                </div>

                {{-- Job Type Badge --}}
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs px-1.5 py-0.5 rounded-full {{ $job->type_color }}">{{ $job->type }}</span>
                    <span class="text-xs px-1.5 py-0.5 rounded-full {{ $job->priority_color }}">{{ $job->priority }}</span>
                </div>

                {{-- Title --}}
                <p class="text-sm font-medium mb-1 leading-tight" :class="dark ? 'text-white' : 'text-gray-900'">
                    {{ Str::limit($job->title, 40) }}
                </p>

                {{-- Job Number --}}
                <p class="text-xs text-primary mb-2">{{ $job->job_number }}</p>

                {{-- Client --}}
                <div class="flex items-center gap-1.5 mb-2">
                    <div class="w-5 h-5 bg-primary/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-primary text-xs font-bold">{{ substr($job->client->name, 0, 1) }}</span>
                    </div>
                    <p class="text-xs text-gray-400 truncate">{{ $job->client->name }}</p>
                </div>

                {{-- Event Date --}}
                @if($job->event_date)
                <div class="flex items-center gap-1 text-xs text-gray-500">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $job->event_date->format('d M Y') }}
                </div>
                @endif
            </div>
            </a>
            @empty
            <div class="rounded-xl p-4 border-2 border-dashed text-center"
                 :style="dark ? 'border-color:#252840' : 'border-color:#e5e7eb'">
                <p class="text-gray-600 text-xs">No jobs</p>
            </div>
            @endforelse
        </div>
    </div>
    @endforeach

</div>

@endsection
