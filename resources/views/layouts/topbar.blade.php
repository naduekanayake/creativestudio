@php
    $pendingReminders = \App\Models\Reminder::where('status', 'Pending')
        ->where('remind_date', '<=', now()->addDays(3))
        ->orderBy('remind_date')->take(5)->get();
    $overdueInvoices = \App\Models\Invoice::where('payment_status', '!=', 'Paid')
        ->where('due_date', '<', now())->count();
    $notifCount = $pendingReminders->count() + $overdueInvoices;
@endphp

<header class="px-6 py-3 flex items-center justify-between flex-shrink-0"
        :style="dark ? 'background:#1a1d2e;border-bottom:1px solid #1e2130' : 'background:#ffffff;border-bottom:1px solid #e5e7eb'">

    {{-- Left: Menu Toggle + Search --}}
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen"
                class="transition-colors"
                :class="dark ? 'text-gray-400 hover:text-white' : 'text-gray-500 hover:text-gray-900'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Search --}}
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" placeholder="Search clients, projects..."
                   class="text-sm rounded-lg pl-10 pr-20 py-2 focus:outline-none focus:border-primary w-72"
                   :style="dark ? 'background:#252840;color:#d1d5db;border:1px solid #2d3154' : 'background:#f9fafb;color:#374151;border:1px solid #e5e7eb'"/>
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500 px-1.5 py-0.5 rounded"
                  :style="dark ? 'background:#2d3154' : 'background:#e5e7eb'">Ctrl+/</span>
        </div>
    </div>

    {{-- Right: Dark Mode + Bell + User --}}
    <div class="flex items-center gap-3">

        {{-- Dark Mode Toggle --}}
        <button @click="dark = !dark"
                class="p-2 rounded-lg transition-colors"
                :class="dark ? 'text-gray-400 hover:bg-dark-700' : 'text-gray-500 hover:bg-gray-100'">
            <svg x-show="dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        </button>

        {{-- Notifications --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false"
                    class="relative transition-colors p-1.5 rounded-lg"
                    :class="dark ? 'text-gray-400 hover:text-white hover:bg-dark-700' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if($notifCount > 0)
                <span class="absolute top-0.5 right-0.5 w-4 h-4 bg-primary text-white text-xs rounded-full flex items-center justify-center">{{ $notifCount }}</span>
                @endif
            </button>

            {{-- Notifications Dropdown --}}
            <div x-show="open" x-cloak x-transition
                 class="absolute right-0 mt-2 w-80 rounded-xl shadow-lg z-50 overflow-hidden"
                 :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">

                <div class="p-3" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
                    <p class="font-semibold text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Notifications</p>
                </div>

                <div class="max-h-80 overflow-y-auto">
                    {{-- Overdue Invoices --}}
                    @if($overdueInvoices > 0)
                    <a href="{{ route('invoices.index') }}" class="block px-3 py-3 transition-colors"
                       :class="dark ? 'hover:bg-dark-700' : 'hover:bg-gray-50'"
                       :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #f3f4f6'">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $overdueInvoices }} overdue invoice(s)</p>
                                <p class="text-xs text-gray-500">Payment pending past due date</p>
                            </div>
                        </div>
                    </a>
                    @endif

                    {{-- Pending Reminders --}}
                    @forelse($pendingReminders as $reminder)
                    <a href="{{ route('reminders.index') }}" class="block px-3 py-3 transition-colors"
                       :class="dark ? 'hover:bg-dark-700' : 'hover:bg-gray-50'"
                       :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #f3f4f6'">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ $reminder->priority === 'High' ? 'bg-red-500/20' : ($reminder->priority === 'Medium' ? 'bg-orange-500/20' : 'bg-blue-500/20') }}">
                                <svg class="w-4 h-4 {{ $reminder->priority === 'High' ? 'text-red-400' : ($reminder->priority === 'Medium' ? 'text-orange-400' : 'text-blue-400') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm truncate" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $reminder->title }}</p>
                                <p class="text-xs text-gray-500">{{ $reminder->remind_date->format('d M Y') }} · {{ $reminder->type }}</p>
                            </div>
                        </div>
                    </a>
                    @empty
                    @if($overdueInvoices === 0)
                    <div class="px-3 py-8 text-center">
                        <p class="text-gray-500 text-sm">No new notifications</p>
                    </div>
                    @endif
                    @endforelse
                </div>

                <a href="{{ route('reminders.index') }}" class="block px-3 py-2.5 text-center text-xs text-primary hover:underline"
                   :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
                    View all reminders
                </a>
            </div>
        </div>

        {{-- User Profile Dropdown --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false"
                    class="flex items-center gap-2 cursor-pointer">
                <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-xs font-bold">
                        {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'U' }}
                    </span>
                </div>
                <div class="text-left">
                    <p class="text-xs font-medium" :class="dark ? 'text-white' : 'text-gray-900'">
                        {{ auth()->check() ? auth()->user()->name : 'User' }}
                    </p>
                    <p class="text-gray-400 text-xs">
                        {{ auth()->check() ? auth()->user()->role_label : 'Studio Owner' }}
                    </p>
                </div>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Profile Dropdown --}}
            <div x-show="open" x-cloak x-transition
                 class="absolute right-0 mt-2 w-56 rounded-xl shadow-lg z-50 overflow-hidden"
                 :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">

                {{-- User Info --}}
                <div class="p-3" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
                    <p class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                </div>

                {{-- Menu Items --}}
                <div class="py-1">
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('users.index') }}"
                       class="flex items-center gap-3 px-3 py-2 text-sm transition-colors"
                       :class="dark ? 'text-gray-300 hover:bg-dark-700' : 'text-gray-700 hover:bg-gray-50'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Manage Users
                    </a>
                    @endif
                    <a href="{{ route('settings.index') }}"
                       class="flex items-center gap-3 px-3 py-2 text-sm transition-colors"
                       :class="dark ? 'text-gray-300 hover:bg-dark-700' : 'text-gray-700 hover:bg-gray-50'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Settings
                    </a>
                </div>

                {{-- Logout --}}
                <div :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-3 px-3 py-2 text-sm text-red-400 hover:bg-red-500/10 transition-colors w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</header>