<header class="bg-dark-800 border-b border-dark-700 px-6 py-3 flex items-center justify-between flex-shrink-0">

    {{-- Left: Menu Toggle + Search --}}
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" 
                class="text-gray-400 hover:text-white transition-colors">
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
                   class="bg-dark-700 text-gray-300 placeholder-gray-500 text-sm rounded-lg pl-10 pr-20 py-2 border border-dark-600 focus:outline-none focus:border-primary w-72"/>
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500 bg-dark-600 px-1.5 py-0.5 rounded">Ctrl+/</span>
        </div>
    </div>

    {{-- Right: Dark Mode + Bell + User --}}
    <div class="flex items-center gap-3">

     {{-- Dark Mode Toggle --}}
<button @click="dark = !dark"
        class="p-2 rounded-lg transition-colors"
        :class="dark ? 'text-gray-400 hover:bg-dark-700' : 'text-gray-500 hover:bg-gray-200'">
    <svg x-show="dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
    </svg>
    <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
    </svg>
</button>

        {{-- Notifications --}}
        <button class="relative text-gray-400 hover:text-white transition-colors p-1.5 rounded-lg hover:bg-dark-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute top-0.5 right-0.5 w-4 h-4 bg-primary text-white text-xs rounded-full flex items-center justify-center">3</span>
        </button>

        {{-- User --}}
        <div class="flex items-center gap-2 cursor-pointer">
            <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
                <span class="text-white text-xs font-bold">JP</span>
            </div>
            <div>
                <p class="text-white text-xs font-medium">John Perera</p>
                <p class="text-gray-400 text-xs">Studio Owner</p>
            </div>
        </div>

    </div>
</header>