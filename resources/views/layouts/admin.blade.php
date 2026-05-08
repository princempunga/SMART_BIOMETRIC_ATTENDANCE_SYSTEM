<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title') - SmartAttend Admin</title>
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
            <div class="w-12 h-12 bg-[#2563EB] rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 21a10.003 10.003 0 008.384-4.562l.054.09A10.003 10.003 0 0112 3c1.259 0 2.455.232 3.559.654m-8.914 4.93L5.5 12l4.5 4.5m5.456-10.89l-4.5 4.5M11 11a2 2 0 114 0 2 2 0 01-4 0z"></path></svg>
            </div>
            <div>
                <div class="font-bold text-xl">SmartAttend</div>
                <div class="text-[11px] text-[#475569] font-medium">ADMIN PANEL</div>
            </div>
        </div>

        <hr class="border-[#1E293B] mx-4">

        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>Dashboard</span>
            </a>

            <div class="group-label">Management</div>
            
            <a href="{{ route('admin.students') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.students') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span>Students</span>
            </a>

            <a href="{{ route('admin.lecturers') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.lecturers') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span>Lecturers</span>
            </a>

            <a href="{{ route('admin.courses') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.courses') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span>Courses</span>
            </a>

            <a href="{{ route('admin.classrooms') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.classrooms') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span>Classrooms</span>
            </a>

            <div class="group-label">Analytics</div>

            <a href="{{ route('admin.attendance') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.attendance') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span>Attendance Logs</span>
            </a>

            <a href="{{ route('admin.reports') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                <span>Reports</span>
            </a>
        </nav>

        <div class="p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-[#EF4444] hover:bg-[#EF4444]/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span class="font-medium">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-[240px] flex flex-col">
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-[#E2E8F0] flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm">
            <h2 class="text-[20px] font-bold text-[#0F172A]">@yield('page_title')</h2>
            
            <div class="flex items-center gap-6">
                <!-- Live Clock -->
                <div id="live-clock" class="text-sm font-semibold text-[#475569] bg-slate-50 px-4 py-1.5 rounded-full border border-slate-100">00:00:00</div>
                
                <button class="text-[#94A3B8] hover:text-[#0F172A] transition-colors relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>

                <div class="flex items-center gap-3">
                    <div class="w-[38px] h-[38px] bg-[#2563EB] rounded-full flex items-center justify-center text-white font-bold text-sm shadow-md">SA</div>
                    <div class="text-sm font-bold text-[#374151]">System Admin</div>
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

        // Modal close logic
        window.onclick = function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.classList.add('hidden');
            }
        }
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay').forEach(m => m.classList.add('hidden'));
            }
        });
    </script>
</body>
</html>
