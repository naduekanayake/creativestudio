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
        <button class="relative transition-colors p-1.5 rounded-lg"
                :class="dark ? 'text-gray-400 hover:text-white hover:bg-dark-700' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute top-0.5 right-0.5 w-4 h-4 bg-primary text-white text-xs rounded-full flex items-center justify-center">3</span>
        </button>

        {{-- User --}}
        <div class="flex items-center gap-2 cursor-pointer">
            <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-white text-xs font-bold">
                    {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'U' }}
                </span>
            </div>
            <div>
                <p class="text-xs font-medium" :class="dark ? 'text-white' : 'text-gray-900'">
                    {{ auth()->check() ? auth()->user()->name : 'User' }}
                </p>
                <p class="text-gray-400 text-xs">
                    {{ auth()->check() ? auth()->user()->position : 'Studio Owner' }}
                </p>
            </div>
        </div>

    </div>
</header>