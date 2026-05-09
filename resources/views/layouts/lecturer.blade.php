<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title') - SmartAttend Lecturer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'DM Sans', sans-serif; background-color: #F1F5F9; }
        .sidebar { background-color: #0F172A; width: 240px; }
        .menu-item { color: #94A3B8; transition: all 0.2s; }
        .menu-item:hover { background-color: #1E293B; color: white; }
        .menu-item.active { background-color: #2563EB; color: white; }
        .group-label { font-size: 10px; color: #475569; letter-spacing: 0.08em; padding: 20px 16px 8px; text-transform: uppercase; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="sidebar fixed inset-y-0 left-0 flex flex-col text-white z-50">
        <div class="p-6 flex items-center gap-3">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
            <div>
                <div class="font-bold text-xl">SmartAttend</div>
                <div class="text-[11px] text-[#475569] font-medium uppercase tracking-widest">Lecturer Portal</div>
            </div>
        </div>

        <hr class="border-[#1E293B] mx-4">

        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('lecturer.dashboard') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('lecturer.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>Dashboard</span>
            </a>

            <div class="group-label">Academic</div>
            
            <a href="{{ route('lecturer.courses') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('lecturer.courses') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span>My Courses</span>
            </a>

            <a href="{{ route('lecturer.sessions') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('lecturer.sessions') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span>Active Sessions</span>
            </a>

            <div class="group-label">Data</div>

            <a href="{{ route('lecturer.attendance') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('lecturer.attendance') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span>Attendance Logs</span>
            </a>
        </nav>

        <div class="p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-[#EF4444] hover:bg-[#EF4444]/10 transition-colors font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-[240px] flex flex-col">
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-[#E2E8F0] flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm">
            <h2 class="text-[18px] font-bold text-[#0F172A]">@yield('page_title')</h2>
            
            <div class="flex items-center gap-6">
                <!-- Live Clock -->
                <div id="live-clock" class="text-sm font-semibold text-[#475569] bg-slate-50 px-4 py-1.5 rounded-full border border-slate-100">00:00:00</div>
                
                <div class="flex items-center gap-3 border-l border-slate-100 pl-6">
                    <div class="w-[38px] h-[38px] bg-[#2563EB] rounded-full flex items-center justify-center text-white font-bold text-sm shadow-md">
                        {{ auth()->user()->name[0] }}
                    </div>
                    <div>
                        <div class="text-sm font-bold text-[#374151]">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] text-[#94A3B8] font-bold uppercase tracking-widest">Lecturer</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="p-8">
            @yield('content')
        </div>
    </main>

    <script>
        // Live Clock
        function updateClock() {
            const now = new Date();
            const clock = document.getElementById('live-clock');
            if(clock) {
                clock.innerText = now.toLocaleTimeString();
            }
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
