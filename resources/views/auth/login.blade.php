<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Attendance Tracker</title>

    {{-- Favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="shortcut icon" href="/favicon.ico">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased bg-stone-50">

<div class="flex items-center justify-center min-h-screen px-4">
    <div class="w-full max-w-md">

        {{-- Logo / Title --}}
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center mb-4 bg-indigo-600 shadow-lg w-14 h-14 rounded-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="text-white w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-stone-800">Attendance Tracker</h1>
            <p class="mt-1 text-sm text-stone-400">Sign in to your account to continue</p>
        </div>

        {{-- Card --}}
        <div class="p-8 bg-white border shadow-sm border-stone-200 rounded-2xl">

            {{-- Session Status --}}
            @if (session('status'))
                <div class="px-4 py-3 mb-4 text-sm border rounded-lg bg-emerald-50 border-emerald-200 text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="text-xs font-medium text-stone-500 uppercase tracking-wide mb-1.5 block">
                        Email Address
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           required autofocus autocomplete="username"
                           class="w-full bg-stone-50 border {{ $errors->has('email') ? 'border-red-300' : 'border-stone-200' }} text-stone-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 placeholder-stone-400">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="text-xs font-medium tracking-wide uppercase text-stone-500">
                            Password
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-indigo-500 transition-colors hover:text-indigo-600">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password"
                           required autocomplete="current-password"
                           class="w-full bg-stone-50 border {{ $errors->has('password') ? 'border-red-300' : 'border-stone-200' }} text-stone-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center gap-2">
                    <input id="remember_me" type="checkbox" name="remember"
                           class="w-4 h-4 cursor-pointer accent-indigo-600">
                    <label for="remember_me" class="text-sm cursor-pointer text-stone-500">
                        Remember me
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                    Sign In
                </button>
            </form>
        </div>

        <p class="mt-6 text-xs text-center text-stone-400">
            Attendance Tracker &copy; {{ date('Y') }} — DinukaEk
        </p>
    </div>
</div>

</body>
</html>