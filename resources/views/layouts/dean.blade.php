<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title') - SmartAttend Dean Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'DM Sans', sans-serif; background-color: #F1F5F9; overflow-x: hidden; }
        .sidebar { background-color: #0F172A; width: 240px; }
        .main-content { margin-left: 240px; }
        .menu-item { color: #94A3B8; transition: all 0.2s; font-size: 13px; }
        .menu-item:hover { background-color: #1E293B; color: white; }
        .menu-item.active { background-color: #2563EB; color: white; }
        .group-label { font-size: 9px; color: #475569; letter-spacing: 0.1em; padding: 12px 16px 4px; text-transform: uppercase; font-weight: 700; }
    </style>
    @stack('styles')
</head>
<body class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="sidebar fixed inset-y-0 left-0 flex flex-col text-white z-50">
        <div class="p-6 flex items-center gap-3">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
            <div>
                <div class="font-bold text-xl">SmartAttend</div>
                <div class="text-[11px] text-[#475569] font-medium uppercase tracking-widest">Dean Portal</div>
            </div>
        </div>

        <hr class="border-[#1E293B] mx-4">

        <!-- Faculty Badge -->
        @if(auth()->user()->faculty)
        <div class="mx-4 mt-3 px-3 py-2 bg-blue-600/10 border border-blue-500/20 rounded-lg">
            <div class="text-[10px] text-blue-400 font-bold uppercase tracking-widest">Your Faculty</div>
            <div class="text-xs text-white font-bold mt-0.5 truncate">{{ auth()->user()->faculty->faculty_name }}</div>
        </div>
        @endif

        <nav class="flex-1 px-3 py-2 space-y-0.5 overflow-y-auto no-scrollbar">
            <a href="{{ route('dean.dashboard') }}" class="menu-item flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('dean.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>Dashboard</span>
            </a>

            <div class="group-label">Academic</div>

            <a href="{{ route('dean.students') }}" class="menu-item flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('dean.students') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span>Students</span>
            </a>

            <a href="{{ route('dean.lecturers') }}" class="menu-item flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('dean.lecturers') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span>Lecturers</span>
            </a>

            <div class="group-label">Data</div>

            <a href="{{ route('dean.attendance') }}" class="menu-item flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('dean.attendance') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span>Attendance Logs</span>
            </a>

            <a href="{{ route('dean.reports') }}" class="menu-item flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('dean.reports') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Analysis Reports</span>
            </a>
        </nav>

        <div class="p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-md text-[#EF4444] hover:bg-[#EF4444]/10 transition-colors font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 main-content flex flex-col">
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-[#E2E8F0] flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm">
            <h2 class="text-[18px] font-bold text-[#0F172A]">@yield('page_title')</h2>

            <div class="flex items-center gap-6">
                <div id="live-clock" class="text-sm font-semibold text-[#475569] bg-slate-50 px-4 py-1.5 rounded-full border border-slate-100">00:00:00</div>

                <div class="flex items-center gap-3 border-l border-slate-100 pl-6">
                    <div class="w-[38px] h-[38px] bg-[#2563EB] rounded-full flex items-center justify-center text-white font-bold text-sm shadow-md">
                        {{ auth()->user()->name[0] }}
                    </div>
                    <div>
                        <div class="text-sm font-bold text-[#374151]">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] text-[#94A3B8] font-bold uppercase tracking-widest">Dean</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="p-8">
            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-600 text-sm font-bold flex items-center gap-3">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        function updateClock() {
            const clock = document.getElementById('live-clock');
            if (clock) clock.innerText = new Date().toLocaleTimeString();
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
