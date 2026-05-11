<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - SmartAttend</title>

    <!-- Google Fonts: DM Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { 
            font-family: 'DM Sans', sans-serif; 
            -webkit-font-smoothing: antialiased;
            background-color: #EFF6FF;
            background-image: radial-gradient(circle, #CBD5E1 1px, transparent 1px);
            background-size: 28px 28px;
            position: relative;
        }
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.8) 0%, rgba(239, 246, 255, 0.2) 100%);
            pointer-events: none;
            z-index: -1;
        }
        /* Dot grid opacity adjustment */
        .dot-overlay {
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle, #CBD5E1 1px, transparent 1px);
            background-size: 28px 28px;
            opacity: 0.4;
            pointer-events: none;
            z-index: -2;
        }
        [x-cloak] { display: none !important; }
        
        /* Hide browser-default password reveal button (Edge/IE) */
        input::-ms-reveal,
        input::-ms-clear {
            display: none;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 relative">
    
    <!-- Dot Overlay for opacity control -->
    <div class="dot-overlay"></div>

    <div class="w-full max-w-[460px] relative z-10">
        
        <!-- Main Login Card -->
        <div class="bg-white rounded-[1.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.06)] border border-slate-100 p-[40px_44px]">
            
            <!-- Logo Block (Inside Card) -->
            <div class="mb-7 flex flex-col items-center">
                <img src="{{ asset('logo.png') }}" alt="SmartAttend Logo" class="w-16 h-16 object-contain mb-3">
                <h1 class="text-3xl font-bold text-[#0F172A] tracking-tight">SmartAttend</h1>
                <p class="text-[11px] font-bold text-[#94A3B8] uppercase tracking-[0.2em] mt-1">Biometric Attendance System</p>
            </div>

            <!-- Horizontal Divider -->
            <hr class="border-[#F1F5F9] my-6">

            <div class="mb-8">
                <h2 class="text-[26px] font-bold text-[#0F172A]">Welcome Back</h2>
                <p class="text-[#94A3B8] text-sm mt-1">Sign in to manage attendance and sessions</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 p-3 bg-blue-50 text-blue-700 rounded-lg text-sm border border-blue-100 italic">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-[12px] font-semibold text-[#374151] tracking-[0.05em] uppercase mb-[8px]">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="lecturer@university.edu"
                        class="w-full h-[48px] px-4 rounded-[10px] bg-[#F8FAFC] border-[1.5px] border-[#E2E8F0] focus:bg-white focus:border-[#2563EB] focus:ring-[3px] focus:ring-[#2563EB]/10 transition-all outline-none text-[#0F172A] text-sm placeholder:text-[#CBD5E1]">
                    @error('email')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div x-data="{ show: false }">
                    <div class="flex items-center justify-between mb-[8px]">
                        <label for="password" class="text-[12px] font-semibold text-[#374151] tracking-[0.05em] uppercase">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[12px] text-[#2563EB] hover:underline font-medium transition-colors">Forgot?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required
                            class="w-full h-[48px] px-4 pr-12 rounded-[10px] bg-[#F8FAFC] border-[1.5px] border-[#E2E8F0] focus:bg-white focus:border-[#2563EB] focus:ring-[3px] focus:ring-[#2563EB]/10 transition-all outline-none text-[#0F172A] text-sm">
                        
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-[#94A3B8] hover:text-slate-600 transition-colors p-1">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.888 9.888L14.2 5.568m2.33 2.33a9.97 9.97 0 011.563 3.029C20.268 15.057 16.477 18 12 18c-.412 0-.817-.025-1.213-.072" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" /></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center gap-3">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-[#CBD5E1] text-[#2563EB] focus:ring-[#2563EB]/20 transition-all cursor-pointer">
                    <label for="remember_me" class="text-sm font-medium text-slate-500 cursor-pointer">Keep me signed in</label>
                </div>

                <!-- Sign In Button -->
                <button type="submit" class="w-full h-[50px] bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold rounded-[10px] transition-all shadow-lg shadow-blue-500/20 transform hover:-translate-y-0.5 active:scale-[0.98] text-[15px] tracking-wide uppercase">
                    Sign In
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-[12px] text-[#CBD5E1] font-medium italic">Access restricted to authorized personnel only &copy; 2026</p>
            </div>
        </div>
    </div>

</body>
</html>
