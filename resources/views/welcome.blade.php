<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor MBG</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="landing-page">
    <main class="landing-shell">
        <span class="badge">Case A · Mini Project</span>
        <h1>Lapor MBG</h1>
        <p class="landing-lead">Laporan keamanan makanan, tercatat dan terpantau.</p>
        <p>Laporkan kejadian, pantau status laporan, dan bantu komunitas menindaklanjuti.</p>

        <div class="landing-actions">
            <a href="{{ route('login') }}" class="btn btn--primary">Masuk</a>
        </div>

        <p class="landing-note">Laravel 13 · MySQL · Blade</p>
    </main>
</body>
</html>
