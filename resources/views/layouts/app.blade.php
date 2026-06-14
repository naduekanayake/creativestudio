<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CreativeStudio') }} - @yield('title', 'Dashboard')</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#7C3AED', hover: '#6D28D9' },
                        dark: {
                            900: '#0f1117',
                            800: '#1a1d2e',
                            700: '#252840',
                            600: '#2d3154',
                        },
                        sidebar: '#13152b',
                    }
                }
            }
        }
    </script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- ApexCharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        * { scrollbar-width: thin; scrollbar-color: #7C3AED #1a1d2e; }
    </style>
</head>

<body x-data="{
        dark: localStorage.getItem('theme') !== 'light',
        sidebarOpen: true,
        notifOpen: false,
        profileOpen: false,
        init() {
            this.$watch('dark', val => {
                localStorage.setItem('theme', val ? 'dark' : 'light');
                if (val) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            });
            if (this.dark) {
                document.documentElement.classList.add('dark');
            }
        }
     }"
     :class="dark ? 'bg-dark-900 text-white' : 'bg-gray-100 text-gray-900'"
     class="antialiased">

<div class="flex h-screen overflow-hidden">
    @include('layouts.sidebar')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('layouts.topbar')
        <main :class="dark ? 'bg-dark-900' : 'bg-gray-100'" class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>