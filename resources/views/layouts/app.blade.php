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
            --brand-accent-hover: #B11A1A;
            --bg: #ffffff;
            --fg: #212529;
            --card-bg: #ffffff;
            --muted: #6c757d;
            --navbar-bg: #f8f9fa;
            --border: #dee2e6;
        }
        html.dark {
            --bg: #0a0a0f;
            --fg: #ffffff; /* high contrast white text */
            --card-bg: #1a1a1f;
            --muted: #b8bcc8;
            --navbar-bg: #15151a;
            --border: #2a2a35;
        }
        body { 
            background: var(--bg); 
            color: var(--fg); 
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .navbar { 
            background: var(--navbar-bg) !important; 
            border-bottom: 1px solid var(--border);
        }
        .card { 
            background: var(--card-bg); 
            border: 1px solid var(--border);
        }
        .text-muted { color: var(--muted) !important; }
        .btn-brand { 
            background-color: var(--brand-accent); 
            border-color: var(--brand-accent); 
            color: #fff; 
        }
        .btn-brand:hover { 
            background-color: var(--brand-accent-hover); 
            border-color: var(--brand-accent-hover); 
            color: #fff; 
        }
        /* Navbar links - better contrast */
        .navbar .navbar-brand,
        .navbar .nav-link { 
            color: var(--fg) !important; 
            transition: color 0.2s ease;
        }
        .navbar .nav-link:hover,
        .navbar .nav-link:focus { 
            color: var(--brand-accent) !important; 
        }
        .navbar .dropdown-menu { 
            background: var(--card-bg); 
            border: 1px solid var(--border);
        }
        .navbar .dropdown-item { 
            color: var(--fg); 
            transition: background-color 0.2s ease;
        }
        .navbar .dropdown-item:hover { 
            background: rgba(139,0,0,0.1); 
            color: var(--brand-accent); 
        }
        /* Form elements */
        .form-control, .form-select {
            background: var(--card-bg);
            border: 1px solid var(--border);
            color: var(--fg);
        }
        .form-control:focus, .form-select:focus {
            background: var(--card-bg);
            border-color: var(--brand-accent);
            color: var(--fg);
            box-shadow: 0 0 0 0.2rem rgba(139,0,0,0.25);
        }
        /* Tables */
        .table-dark {
            --bs-table-bg: var(--card-bg);
            --bs-table-color: var(--fg);
            --bs-table-border-color: var(--border);
        }
        /* Alerts */
        .alert-success {
            background-color: rgba(29, 185, 84, 0.1);
            border-color: #1DB954;
            color: #1DB954;
        }
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border-color: #dc3545;
            color: #dc3545;
        }
        .alert-warning {
            background-color: rgba(242, 201, 76, 0.1);
            border-color: #F2C94C;
            color: #F2C94C;
        }
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
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.payouts.index') }}">Manage Payouts</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.support.index') }}">Support Tickets</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('payment.index') }}">Payment Management</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.analytics.index') }}">Analytics</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.switch.user') }}">Switch to User View</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.profile') }}">Profile</a></li>
                        @else
                            <!-- User Menu -->
                            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('account') }}">Account Management</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('roadmap') }}">Roadmap</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('affiliate') }}">Affiliate</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('expert.advisors') }}">Expert Advisors</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('support.index') }}">Support</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('plans') }}">Plans</a></li>
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
