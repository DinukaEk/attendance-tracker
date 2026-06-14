<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Tracker — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased bg-stone-50 text-stone-800">

<div class="flex flex-col min-h-screen">

    {{-- Top Navbar --}}
    <nav class="sticky top-0 z-50 flex items-center justify-between px-6 py-3 bg-white border-b shadow-sm border-stone-200">
        <div class="flex items-center gap-8">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-lg font-bold tracking-wide text-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                AttendanceTracker
            </a>

            <div class="items-center hidden gap-1 md:flex">
                <a href="{{ route('dashboard') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-stone-500 hover:text-stone-900 hover:bg-stone-100' }}">
                    Dashboard
                </a>
                <a href="{{ route('attendance.index') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('attendance.*') ? 'bg-indigo-600 text-white' : 'text-stone-500 hover:text-stone-900 hover:bg-stone-100' }}">
                    Mark Attendance
                </a>
                <a href="{{ route('students.search') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('students.search') || request()->routeIs('students.show') ? 'bg-indigo-600 text-white' : 'text-stone-500 hover:text-stone-900 hover:bg-stone-100' }}">
                    Search Student
                </a>
                <a href="{{ route('students.manage.index') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('students.manage.*') ? 'bg-indigo-600 text-white' : 'text-stone-500 hover:text-stone-900 hover:bg-stone-100' }}">
                    Manage Students
                </a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.teachers.index') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.*') ? 'bg-indigo-600 text-white' : 'text-stone-500 hover:text-stone-900 hover:bg-stone-100' }}">
                    Manage Teachers
                </a>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="hidden text-sm text-stone-400 md:block">{{ auth()->user()->name }}</span>
            <span class="text-xs px-2 py-1 rounded-full {{ auth()->user()->isAdmin() ? 'bg-indigo-100 text-indigo-700' : 'bg-stone-100 text-stone-500' }}">
                {{ ucfirst(auth()->user()->role) }}
            </span>
            <a href="{{ route('profile.edit') }}" class="text-sm transition-colors text-stone-400 hover:text-stone-700">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm px-3 py-1.5 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-600 hover:text-stone-800 transition-colors">
                    Log out
                </button>
            </form>
        </div>
    </nav>

    {{-- Page Content --}}
    <main class="flex-1 w-full px-6 py-8 mx-auto max-w-7xl">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="py-4 text-xs text-center bg-white border-t border-stone-200 text-stone-400">
        Attendance Tracker &copy; {{ date('Y') }} — DinukaEk
    </footer>

</div>

</body>
</html>