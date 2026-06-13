<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Tracker - @yield('title', 'Home')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite('resources/js/app.js')
</head>
<body>
    <nav class="mb-4 navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Attendance Tracker</a>
            <div class="navbar-nav">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold text-white' : 'text-white' }}" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link {{ request()->routeIs('students.*') ? 'active fw-bold text-white' : 'text-white' }}" href="{{ route('students.search') }}">Search Student</a>
                <a class="nav-link {{ request()->routeIs('attendance.index') ? 'active fw-bold text-white' : 'text-white' }}" href="{{ route('attendance.index') }}">Mark Attendance</a>
                @if(auth()->user()->isAdmin())
                    <a class="nav-link {{ request()->routeIs('admin.teachers.*') ? 'active fw-bold text-white' : 'text-white' }}" href="{{ route('admin.teachers.index') }}">Manage Teachers</a>
                @endif
                <a class="text-white nav-link" href="{{ route('profile.edit') }}">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-white bg-transparent border-0 nav-link">Log Out</button>
                </form>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>