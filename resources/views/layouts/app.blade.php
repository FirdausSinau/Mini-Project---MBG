<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') · Lapor MBG</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="app-page">
    <a href="#main" class="skip-link">Lewati ke konten</a>

    <header class="app-header">
        <div class="app-header__top">
            <a href="{{ route('dashboard') }}" class="app-brand">Lapor MBG</a>

            <div class="app-user">
                <span class="app-user__name">{{ auth()->user()->nama_lengkap }}</span>
                <span class="role-badge">{{ auth()->user()->roleLabel() }}</span>
            </div>
        </div>

        <nav class="app-nav" aria-label="Navigasi utama">
            <a href="{{ route('dashboard') }}"
               class="app-nav__link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                Dashboard
            </a>

            @if (auth()->user()->isReporter())
                <a href="{{ route('reports.create') }}"
                   class="app-nav__link {{ request()->routeIs('reports.create') ? 'is-active' : '' }}">
                    Buat Laporan
                </a>
            @endif

            @if (auth()->user()->isCoordinator())
                <a href="{{ route('master-data.index') }}"
                   class="app-nav__link {{ request()->routeIs('master-data.*') ? 'is-active' : '' }}">
                    Data Induk
                </a>
            @endif

            {{-- Link laporan dan rekap ditambahkan saat halamannya sudah ada. --}}

            <form method="POST" action="{{ route('logout') }}" class="app-nav__logout">
                @csrf
                <button type="submit" class="btn btn--ghost btn--sm">Keluar</button>
            </form>
        </nav>
    </header>

    <main id="main" class="app-main">
        @if (session('success'))
            <div class="alert alert--success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert--danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <footer class="app-footer">
        <p>Lapor MBG · Mini Project Proyek 3</p>
    </footer>
</body>
</html>
