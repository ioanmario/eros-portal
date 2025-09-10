<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Optional font -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Alpine (deferred) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Vite (your app assets) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            --brand-accent: #8B0000; /* vampire red */
            --bg: #ffffff;
            --fg: #212529;
            --card-bg: #ffffff;
            --muted: #6c757d;
            --navbar-bg: #f8f9fa;
        }
        html.dark {
            --bg: #0f0f12;
            --fg: #8B0000; /* vampire red text in dark mode */
            --card-bg: #141419;
            --muted: #a1a1aa;
            --navbar-bg: #121217;
        }
        body { background: var(--bg); color: var(--fg); }
        .navbar { background: var(--navbar-bg) !important; }
        .card { background: var(--card-bg); }
        .text-muted { color: var(--muted) !important; }
        .btn-brand { background-color: var(--brand-accent); border-color: var(--brand-accent); color: #fff; }
        .btn-brand:hover { filter: brightness(1.1); color: #fff; }
        /* Dark theme navbar links use vampire red */
        html.dark .navbar .navbar-brand,
        html.dark .navbar .nav-link { color: var(--brand-accent) !important; }
        html.dark .navbar .nav-link:hover,
        html.dark .navbar .nav-link:focus { color: #b11a1a !important; }
        html.dark .navbar .dropdown-menu { background: var(--card-bg); }
        html.dark .navbar .dropdown-item { color: var(--brand-accent); }
        html.dark .navbar .dropdown-item:hover { background: rgba(139,0,0,0.12); color: #ff5a5a; }
    </style>
    <script>
        (function(){
            try {
                const pref = localStorage.getItem('theme');
                if (pref === 'dark' || (!pref && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        })();
    </script>
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md shadow-sm">
        <div class="container">
            <!-- Brand points to Broker Sync (as requested) -->
            <a class="navbar-brand" href="{{ route('broker.sync.select') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        {{-- Role-based navigation --}}
                        @if(method_exists(Auth::user(), 'hasRole') && Auth::user()->hasRole('admin'))
                            <!-- Admin Menu -->
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">Manage Users</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.switch.user') }}">Switch to User View</a></li>
                        @else
                            <!-- User Menu -->
                            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('account') }}">Account Management</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('roadmap') }}">Roadmap</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('affiliate') }}">Affiliate</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('expert.advisors') }}">Expert Advisors</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('support') }}">Support</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('profile') }}">Profile</a></li>
                        @endif

                        
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>

<!-- Bootstrap JS bundle (Popper included) - added here so navbar toggles work if not bundled in your Vite assets -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        // Navbar no longer controls theme; handled on Profile page only
    });
</script>
</body>
</html>
