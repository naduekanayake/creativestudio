<aside class="w-44 bg-sidebar flex flex-col flex-shrink-0 h-full"
       x-show="sidebarOpen">

    {{-- Logo --}}
    <div class="p-4 border-b border-dark-700">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-white font-bold text-xs leading-tight">CreativeStudio</p>
                <p class="text-gray-400 text-xs">Photography & Films</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-0.5">

        <x-nav-item href="{{ route('dashboard') }}" icon="home" label="Dashboard"/>
        <x-nav-item href="{{ route('clients.index') }}" icon="users" label="Clients"/>
        <x-nav-item href="{{ route('packages.index') }}" icon="package" label="Packages"/>

        {{-- Quotations --}}
        <x-nav-dropdown label="Quotations" icon="document">
            <x-nav-sub-item href="{{ route('quotations.index') }}" label="All Quotations"/>
            <x-nav-sub-item href="{{ route('quotations.create') }}" label="Create Quotation"/>
        </x-nav-dropdown>

        {{-- Invoices --}}
        <x-nav-dropdown label="Invoices" icon="receipt">
            <x-nav-sub-item href="{{ route('invoices.index') }}" label="All Invoices"/>
            <x-nav-sub-item href="{{ route('invoices.create') }}" label="Create Invoice"/>
        </x-nav-dropdown>

        <x-nav-item href="{{ route('payments.index') }}" icon="credit-card" label="Payments"/>
        <x-nav-item href="{{ route('jobs.index') }}" icon="kanban" label="Job Management"/>
        <x-nav-item href="{{ route('deliverables.index') }}" icon="truck" label="Deliverables"/>
        <x-nav-item href="{{ route('calendar') }}" icon="calendar" label="Calendar"/>
        <x-nav-item href="{{ route('whatsapp') }}" icon="whatsapp" label="WhatsApp"/>
        <x-nav-item href="{{ route('email-sharing') }}" icon="mail" label="Email Sharing"/>
        <x-nav-item href="{{ route('reminders.index') }}" icon="bell" label="Reminders"/>

        {{-- Reports --}}
        <x-nav-dropdown label="Reports" icon="chart">
            <x-nav-sub-item href="{{ route('reports.index') }}" label="Overview"/>
            <x-nav-sub-item href="{{ route('reports.financial') }}" label="Financial"/>
        </x-nav-dropdown>

        <x-nav-item href="{{ route('activity-log') }}" icon="activity" label="Activity Log"/>
        <x-nav-item href="#" icon="dollar" label="Expenses"/>
        <x-nav-item href="#" icon="file-text" label="Contracts"/>

        {{-- Admin Only --}}
        @if(auth()->check() && auth()->user()->isAdmin())
        <x-nav-item href="{{ route('users.index') }}" icon="users" label="Users"/>
        @endif

        <x-nav-item href="#" icon="settings" label="Settings"/>

    </nav>

    {{-- User Profile --}}
    <div class="p-3 border-t border-dark-700">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 bg-primary rounded-full flex items-center justify-center">
                <span class="text-white text-xs font-bold">
                    {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'U' }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-xs font-medium truncate">
                    {{ auth()->check() ? auth()->user()->name : 'User' }}
                </p>
                <p class="text-gray-400 text-xs truncate">
                    {{ auth()->check() ? auth()->user()->role_label : '' }}
                </p>
            </div>
            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-red-400 transition-colors" title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

</aside>