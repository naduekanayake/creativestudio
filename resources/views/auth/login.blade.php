<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login — Creative Shop </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: '#3b82f6' } } }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px #252840 inset !important;
            -webkit-text-fill-color: #fff !important;
        }
        .gradient-panel {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e293b 55%, #0f1117 100%);
        }
    </style>
</head>
<body class="min-h-screen flex" x-data="{ showPass: false }" style="background:#0f1117">

    {{-- Left Panel — Brand --}}
    <div class="hidden lg:flex lg:w-1/2 gradient-panel relative overflow-hidden">

        {{-- Decorative glows --}}
        <div class="absolute top-10 right-10 w-40 h-40 rounded-full bg-blue-400/10 blur-2xl"></div>
        <div class="absolute bottom-20 left-10 w-56 h-56 rounded-full bg-blue-500/10 blur-3xl"></div>

        <div class="relative z-10 flex flex-col justify-between p-12 text-white w-full">

            {{-- Logo --}}
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white/15 backdrop-blur rounded-2xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold">Creative Shop </h2>
                    <p class="text-white/60 text-sm">Photography & Films</p>
                </div>
            </div>

            {{-- Middle Quote --}}
            <div>
                <h1 class="text-4xl font-bold leading-tight mb-4">
                    Capture moments.<br/>Manage your studio.
                </h1>
                <p class="text-white/70 text-lg">
                    All-in-one POS system for photography & videography professionals — clients, bookings, invoices, and more.
                </p>
            </div>

            {{-- Bottom Features --}}
            <div class="flex gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-white/15 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                        </svg>
                    </div>
                    <span class="text-white/70 text-sm">Invoices</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-white/15 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-white/70 text-sm">Bookings</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-white/15 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                        </svg>
                    </div>
                    <span class="text-white/70 text-sm">Reports</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Panel — Login Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6">
        <div class="w-full max-w-md">

            {{-- Mobile Logo --}}
            <div class="lg:hidden text-center mb-8">
                <div class="w-14 h-14 bg-primary rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-white">Creative Studio</h1>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-white mb-1">Welcome back! 👋</h2>
                <p class="text-gray-400 text-sm">Sign in to your account to continue</p>
            </div>

            {{-- Errors --}}
            @if($errors->any())
            <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ $errors->first() }}
            </div>
            @endif
            @if(session('error'))
            <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label class="text-gray-400 text-xs mb-1.5 block">Email Address</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full text-sm rounded-xl pl-10 pr-4 py-3 text-white focus:outline-none focus:border-primary transition-colors"
                               style="background:#252840;border:1px solid #2d3154"
                               placeholder="admin@creativestudio.lk"/>
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label class="text-gray-400 text-xs mb-1.5 block">Password</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input :type="showPass ? 'text' : 'password'" name="password" required
                               class="w-full text-sm rounded-xl pl-10 pr-10 py-3 text-white focus:outline-none focus:border-primary transition-colors"
                               style="background:#252840;border:1px solid #2d3154"
                               placeholder="••••••••"/>
                        <button type="button" @click="showPass = !showPass"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300">
                            <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded" style="accent-color:#3b82f6"/>
                        <span class="text-gray-400 text-sm">Remember me</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-primary hover:bg-blue-700 text-white font-medium py-3 rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                    Sign In
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </button>
            </form>

            <p class="text-center text-gray-600 text-xs mt-8">
                © {{ date('Y') }} Creative Studio Photography & Films
            </p>
        </div>
    </div>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>
</html>