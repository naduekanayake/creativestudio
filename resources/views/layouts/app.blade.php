<!DOCTYPE html>
<html lang="en" x-data="{ dark: localStorage.getItem('dark') === 'true', sidebarOpen: window.innerWidth > 1024 }"
      x-init="$watch('dark', val => localStorage.setItem('dark', val))"
      :class="dark ? 'dark' : ''">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>@yield('title', 'CreativeStudio') — CreativeStudio POS</title>

    {{-- Dark mode — Alpine load වෙන්න කලින්ම apply කරනවා (flash නවත්වයි) --}}
    <script>
        (function() {
            if (localStorage.getItem('dark') === 'true') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#7C3AED',
                        'primary-hover': '#6D28D9',
                        sidebar: '#13151f',
                        'dark-700': '#1e2130',
                        'dark-600': '#252840',
                    }
                }
            }
        }
    </script>

    {{-- Alpine.js CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { background: #0f1117; }
        .bg-sidebar { background: #13151f; }
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: #1a1d2e; }
        ::-webkit-scrollbar-thumb { background: #7C3AED; border-radius: 2px; }

        /* Alpine load වෙනකම් header + sidebar flash වීම නවත්වයි */
        header [x-data],
        aside {
            transition: none;
        }
    </style>
</head>
<body class="min-h-screen" :class="dark ? 'bg-dark-900' : 'bg-gray-50'" style="background:#0f1117">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    @include('layouts.sidebar')

   {{-- Mobile backdrop (sidebar open වෙද්දී, phone එකේ විතරක්) --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 z-20 lg:hidden"
         x-transition.opacity
         style="display:none"></div>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Topbar --}}
        @include('layouts.topbar')

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-6" :style="dark ? 'background:#0f1117' : 'background:#f9fafb'">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
