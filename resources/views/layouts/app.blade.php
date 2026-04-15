<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BILLATEA Shop') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700|sora:400,500,600,700" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="shop-shell">
    <div id="app">
        <div class="topbar-note">
            <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
                <span>Freshly brewed milk tea, fruit coolers, and café-style favorites.</span>
                <span class="topbar-note__meta">Open daily 10:00 AM - 9:00 PM</span>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-dark app-navbar sticky-top">
            <div class="container">
                <a class="navbar-brand fw-bold d-flex align-items-center gap-3" href="{{ route('catalog.index') }}">
                    <span class="brand-mark">B</span>
                    <span>
                        <span class="d-block">BILLATEA Shop</span>
                        <small class="brand-subtitle">Crafted drinks and quick pickup</small>
                    </span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="{{ route('catalog.index') }}">Menu</a></li>
                        @auth
                            @if (auth()->user()->hasAnyRole('admin', 'staff'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('reports.index') }}">Reports</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.orders.index') }}">Orders</a></li>
                            @endif

                            @if (auth()->user()->isAdmin())
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.drinks.index') }}">Manage Drinks</a></li>
                            @endif
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                        @guest
                            <li class="nav-item">
                                <div class="guest-auth-actions d-flex flex-wrap gap-2">
                                    <a class="btn btn-outline-cta btn-sm px-3" href="{{ route('login') }}">Login</a>
                                    <a class="btn btn-cta btn-sm px-3" href="{{ route('register') }}">Register</a>
                                </div>
                            </li>
                        @else
                            <li class="nav-item"><span class="nav-link text-white-50">{{ auth()->user()->role }}</span></li>
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-cta btn-sm px-3">Logout</button>
                                </form>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                    <a class="dropdown-item" href="{{ route('home') }}">Dashboard</a>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="pb-5">
            @if (session('status'))
                <div class="container pt-4">
                    <div class="alert alert-success shadow-sm border-0">{{ session('status') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="container pt-4">
                    <div class="alert alert-danger shadow-sm border-0">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
