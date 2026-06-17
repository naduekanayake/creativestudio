@extends('layouts.app')

@section('title', 'User Management')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">User Management</h1>
        <p class="text-gray-400 text-sm mt-0.5">Manage system users and permissions</p>
    </div>
    <a href="{{ route('users.create') }}"
       class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add User
    </a>
</div>

{{-- Messages --}}
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

{{-- Users Grid --}}
<div class="grid grid-cols-3 gap-4">
    @forelse($users as $user)
    <div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">

        {{-- Avatar & Info --}}
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold text-lg">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <div>
                    <p class="font-semibold text-sm" :class="dark ? 'text-white' : 'text-gray-900'">{{ $user->name }}</p>
                    <p class="text-gray-400 text-xs">{{ $user->position ?? 'No position' }}</p>
                </div>
            </div>
            <span class="px-2 py-0.5 rounded-full text-xs {{ $user->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                {{ $user->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        {{-- Details --}}
        <div class="space-y-2 mb-4">
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="truncate">{{ $user->email }}</span>
            </div>
            @if($user->phone)
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                {{ $user->phone }}
            </div>
            @endif
            <div>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $user->role_color }}">
                    {{ $user->role_label }}
                </span>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2 pt-3" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
            <a href="{{ route('users.edit', $user) }}"
               class="flex-1 text-center text-xs font-medium py-1.5 rounded-lg transition-colors"
               :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-gray-300' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                Edit
            </a>

            @if($user->id !== auth()->id())
                <form method="POST" action="{{ route('users.toggle-status', $user) }}" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="w-full text-xs font-medium py-1.5 rounded-lg transition-colors {{ $user->is_active ? 'bg-orange-500/20 text-orange-400 hover:bg-orange-500/30' : 'bg-green-500/20 text-green-400 hover:bg-green-500/30' }}">
                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>

                <form method="POST" action="{{ route('users.destroy', $user) }}"
                      onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="text-xs font-medium px-3 py-1.5 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            @else
                <span class="flex-1 text-center text-xs text-gray-600 py-1.5">You</span>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-3 rounded-xl p-8 text-center"
         :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <p class="text-gray-500 text-sm">No users found.</p>
    </div>
    @endforelse
</div>

@endsection