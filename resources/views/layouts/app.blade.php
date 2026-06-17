<!DOCTYPE html>
<html lang="en" x-data="{ dark: localStorage.getItem('dark') === 'true', sidebarOpen: true }"
      x-init="$watch('dark', val => localStorage.setItem('dark', val))"
      :class="dark ? 'dark' : ''">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>@yield('title', 'CreativeStudio') — CreativeStudio POS</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
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
    </style>
</head>
<body class="min-h-screen" :class="dark ? 'bg-dark-900' : 'bg-gray-50'" style="background:#0f1117">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    @include('layouts.sidebar')

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