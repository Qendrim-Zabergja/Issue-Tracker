<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Issue Tracker')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

<nav class="navbar">
    <a href="{{ route('projects.index') }}" class="navbar-brand">&#9654; Issue Tracker</a>
    <div class="navbar-nav">
        <a href="{{ route('projects.index') }}" class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">Projects</a>
        <a href="{{ route('issues.index') }}"   class="nav-link {{ request()->routeIs('issues.*') ? 'active' : '' }}">Issues</a>
        <a href="{{ route('tags.index') }}"     class="nav-link {{ request()->routeIs('tags.*') ? 'active' : '' }}">Tags</a>
    </div>
    <div class="navbar-user">
        <span>{{ auth()->user()->name }}</span>
        <form action="{{ route('logout') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="btn btn-sm" style="background:rgba(255,255,255,.1);color:#e0e7ff;">Logout</button>
        </form>
    </div>
</nav>

<div class="container">
    @include('partials.flash')
    @yield('content')
</div>

@stack('scripts')
</body>
</html>
