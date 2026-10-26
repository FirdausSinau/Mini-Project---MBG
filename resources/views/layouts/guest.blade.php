<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lapor MBG') · Lapor MBG</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="guest-page">
    <main class="guest-shell">
        <section class="guest-brand">
            <a href="{{ route('home') }}" class="brand-mark">Lapor MBG</a>

            <div class="guest-brand__content">
                <span class="badge">Case A · Mini Project</span>
                <h1>Laporan keamanan makanan, tercatat dan terpantau.</h1>
                <p>Laporkan kejadian, pantau status, dan bantu komunitas menindaklanjuti.</p>

                <ul class="guest-points">
                    <li>Laporan terikat ke sekolah dan distribusi yang jelas.</li>
                    <li>Pelapor hanya melihat laporan miliknya sendiri.</li>
                    <li>Pemantau dan koordinator bekerja sesuai kewenangan.</li>
                </ul>
            </div>

            <p class="guest-brand__note">Laravel 13 · MySQL · Blade</p>
        </section>

        <section class="guest-panel">
            @yield('content')
        </section>
    </main>
</body>
</html>
