<!DOCTYPE html>
<html lang="en" 
      x-data="{ dark: true, sidebarOpen: true }" 
      :class="{ 'dark': dark }"
      class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CreativeStudio') }} - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body class="bg-dark-900 text-white font-sans antialiased" x-cloak>

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Top Bar --}}
            @include('layouts.topbar')

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto bg-dark-900 p-6">
                @yield('content')
            </main>

        </div>
    </div>

</body>
</html>