<aside class="w-44 flex flex-col flex-shrink-0 h-full fixed inset-y-0 left-0 z-30 lg:relative lg:z-auto"
       x-show="sidebarOpen"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full"
       :style="dark ? 'background:#13151f' : 'background:#ffffff;border-right:1px solid #e5e7eb'">

    {{-- Logo --}}
    @php $studioLogo = \App\Models\Setting::logoUrl(); @endphp
    <div class="p-4" :style="dark ? 'border-bottom:1px solid #1e2130' : 'border-bottom:1px solid #e5e7eb'">
        <div class="flex items-center gap-2">
            @if($studioLogo)
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden bg-white">
                <img src="{{ $studioLogo }}" class="w-full h-full object-contain"/>
            </div>
            @else
            <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            @endif
            <div>
                <p class="font-bold text-xs leading-tight" :class="dark ? 'text-white' : 'text-gray-900'">{{ \App\Models\Setting::get('studio_name', 'CreativeStudio') }}</p>
                <p class="text-gray-400 text-xs">{{ \App\Models\Setting::get('studio_tagline', 'Photography & Films') }}</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-0.5">

        {{-- MAIN --}}
        <x-nav-item href="{{ route('dashboard') }}" icon="home" label="Dashboard"/>

        {{-- ===== SALES ===== --}}
        <p class="text-gray-500 text-[10px] font-semibold uppercase tracking-wider px-3 pt-3 pb-1">Sales</p>
        <x-nav-item href="{{ route('clients.index') }}" icon="users" label="Clients"/>

        @if(auth()->check() && auth()->user()->isAdmin())
        <x-nav-item href="{{ route('packages.index') }}" icon="package" label="Packages"/>

        <x-nav-dropdown label="Quotations" icon="document">
            <x-nav-sub-item href="{{ route('quotations.index') }}" label="All Quotations"/>
            <x-nav-sub-item href="{{ route('quotations.create') }}" label="Create Quotation"/>
        </x-nav-dropdown>

        <x-nav-item href="{{ route('contracts.index') }}" icon="file-text" label="Contracts"/>
        @endif

        {{-- ===== OPERATIONS ===== --}}
        <p class="text-gray-500 text-[10px] font-semibold uppercase tracking-wider px-3 pt-3 pb-1">Operations</p>
        <x-nav-item href="{{ route('jobs.index') }}" icon="kanban" label="Job Management"/>
        <x-nav-item href="{{ route('calendar') }}" icon="calendar" label="Calendar"/>
        <x-nav-item href="{{ route('deliverables.index') }}" icon="truck" label="Deliverables"/>
        <x-nav-item href="{{ route('reminders.due') }}" icon="bell" label="Reminders"/>

        {{-- ===== FINANCE (admin+) ===== --}}
        @if(auth()->check() && auth()->user()->isAdmin())
        <p class="text-gray-500 text-[10px] font-semibold uppercase tracking-wider px-3 pt-3 pb-1">Finance</p>

        <x-nav-dropdown label="Invoices" icon="receipt">
            <x-nav-sub-item href="{{ route('invoices.index') }}" label="All Invoices"/>
            <x-nav-sub-item href="{{ route('invoices.create') }}" label="Create Invoice"/>
        </x-nav-dropdown>

        <x-nav-item href="{{ route('payments.index') }}" icon="credit-card" label="Payments"/>
        <x-nav-item href="{{ route('expenses.index') }}" icon="dollar" label="Expenses"/>
        @endif

        {{-- ===== COMMUNICATION ===== --}}
        <p class="text-gray-500 text-[10px] font-semibold uppercase tracking-wider px-3 pt-3 pb-1">Communication</p>
        <x-nav-item href="{{ route('whatsapp') }}" icon="whatsapp" label="WhatsApp"/>
        <x-nav-item href="{{ route('email-sharing') }}" icon="mail" label="Email Sharing"/>

        {{-- ===== ADMIN (admin+) ===== --}}
        @if(auth()->check() && auth()->user()->isAdmin())
        <p class="text-gray-500 text-[10px] font-semibold uppercase tracking-wider px-3 pt-3 pb-1">Admin</p>

        <x-nav-dropdown label="Reports" icon="chart">
            <x-nav-sub-item href="{{ route('reports.index') }}" label="Overview"/>
            <x-nav-sub-item href="{{ route('reports.financial') }}" label="Financial"/>
        </x-nav-dropdown>

        <x-nav-item href="{{ route('activity-log') }}" icon="activity" label="Activity Log"/>
        <x-nav-item href="{{ route('users.index') }}" icon="users" label="Users"/>
        @endif

        {{-- Super Admin only --}}
        @if(auth()->check() && auth()->user()->isSuperAdmin())
        <x-nav-item href="{{ route('settings.index') }}" icon="settings" label="Settings"/>
        @endif

    </nav>

    {{-- User Profile --}}
    <div class="p-3" :style="dark ? 'border-top:1px solid #1e2130' : 'border-top:1px solid #e5e7eb'">
        <div class="flex items-center gap-2">
            <a href="{{ route('profile.edit') }}" class="w-7 h-7 bg-primary rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden">
                @if(auth()->check() && auth()->user()->avatar)
                <img src="{{ auth()->user()->avatar_url }}" class="w-full h-full object-cover"/>
                @else
                <span class="text-white text-xs font-bold">
                    {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'U' }}
                </span>
                @endif
            </a>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-medium truncate" :class="dark ? 'text-white' : 'text-gray-900'">
                    {{ auth()->check() ? auth()->user()->name : 'User' }}
                </p>
                <p class="text-gray-400 text-xs truncate">
                    {{ auth()->check() ? auth()->user()->role_label : '' }}
                </p>
            </div>
            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-red-400 transition-colors" title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

</aside>
