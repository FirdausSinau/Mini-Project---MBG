<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor MBG</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #f7f4ee;
            color: #1f2937;
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
        }
        main {
            width: min(100% - 2rem, 640px);
            padding: 2rem;
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: .35rem .75rem;
            border-radius: 999px;
            background: #e6f4f1;
            color: #0f5c55;
            font-size: .85rem;
            font-weight: 600;
        }
        h1 {
            margin: 1rem 0 .75rem;
            font-size: clamp(2.25rem, 6vw, 3.5rem);
            letter-spacing: -.03em;
            color: #0f5c55;
        }
        p {
            margin: 0 auto 1rem;
            max-width: 46ch;
            line-height: 1.65;
            color: #6b7280;
        }
        .note {
            margin-top: 2rem;
            font-size: .85rem;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <main>
        <span class="badge">Case A · Mini Project</span>
        <h1>Lapor MBG</h1>
        <p>Laporan keamanan makanan, tercatat dan terpantau.</p>
        <p>Aplikasi sedang disiapkan. Halaman login dan fitur pelaporan akan ditambahkan pada langkah berikutnya.</p>
        <p class="note">Laravel 13 · MySQL · Blade</p>
    </main>
</body>
</html>
