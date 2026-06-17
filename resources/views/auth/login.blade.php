<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login — Creative Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#7C3AED',
                    }
                }
            }
        }
    </script>
    <style>
        body { background: #0f1117; font-family: 'Inter', sans-serif; }
        .glass { background: #1a1d2e; border: 1px solid #252840; }
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px #252840 inset !important;
            -webkit-text-fill-color: #fff !important;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">Creative Studio</h1>
            <p class="text-gray-400 text-sm mt-1">Photography & Films — POS System</p>
        </div>

        {{-- Login Card --}}
        <div class="glass rounded-2xl p-8">
            <h2 class="text-lg font-semibold text-white mb-1">Welcome back!</h2>
            <p class="text-gray-400 text-sm mb-6">Sign in to your account to continue</p>

            {{-- Error --}}
            @if($errors->any())
            <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            {{-- Session Error --}}
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
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500 transition-colors text-white"
                           style="background:#252840;border:1px solid #2d3154"
                           placeholder=""/>
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label class="text-gray-400 text-xs mb-1.5 block">Password</label>
                    <input type="password" name="password" required
                           class="w-full text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500 transition-colors text-white"
                           style="background:#252840;border:1px solid #2d3154"
                           placeholder=""/>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded" style="accent-color:#7C3AED"/>
                        <span class="text-gray-400 text-sm">Remember me</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 rounded-xl transition-colors text-sm">
                    Sign In
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-gray-600 text-xs mt-6">
            © 2026 Creative Studio Photography & Films. All rights reserved.
        </p>
    </div>

</body>
</html>