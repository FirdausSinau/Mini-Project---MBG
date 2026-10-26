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
        <a href="{{ route('dashboard') }}" class="app-brand">Lapor MBG</a>

        <div class="app-header__actions">
            <span class="role-badge">{{ auth()->user()->roleLabel() }}</span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn--ghost">Keluar</button>
            </form>
        </div>
    </header>

    <main id="main" class="app-main">
        @yield('content')
    </main>
</body>
</html>
