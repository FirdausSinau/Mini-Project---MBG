@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Dashboard</p>
        <h1>Selamat datang, {{ auth()->user()->nama_lengkap }}</h1>
        <p>Kamu masuk sebagai <strong>{{ auth()->user()->roleLabel() }}</strong>. Halaman ini menjadi titik awal pekerjaanmu.</p>
    </section>

    @if (auth()->user()->isReporter())
        <section class="role-guide">
            <div class="card">
                <span class="badge">Peran kamu</span>
                <h2>Pelapor</h2>
                <p>Kamu dapat membuat laporan kejadian, menyertakan bukti, dan memantau status laporan milikmu sendiri.</p>
            </div>

            <div class="card">
                <span class="badge">Yang bisa kamu lakukan</span>
                <ul class="check-list">
                    <li>Membuat laporan dengan konteks sekolah dan distribusi.</li>
                    <li>Mengunggah satu bukti pendukung.</li>
                    <li>Memperbaiki laporan selama masih diizinkan.</li>
                    <li>Melihat perkembangan laporan sendiri.</li>
                </ul>
            </div>

            <div class="card">
                <span class="badge">Berikutnya</span>
                <h2>Fitur laporan segera hadir</h2>
                <p>Form laporan, upload bukti, dan daftar laporan akan ditambahkan pada langkah berikutnya.</p>
            </div>
        </section>
    @elseif (auth()->user()->isReviewer())
        <section class="role-guide">
            <div class="card">
                <span class="badge">Peran kamu</span>
                <h2>Pemantau Komunitas</h2>
                <p>Kamu meninjau kelengkapan laporan, melihat laporan terkait, dan menandai laporan yang perlu diteruskan.</p>
            </div>

            <div class="card">
                <span class="badge">Yang bisa kamu lakukan</span>
                <ul class="check-list">
                    <li>Melihat semua laporan yang masuk.</li>
                    <li>Melihat laporan lain pada distribusi yang sama.</li>
                    <li>Memberikan catatan peninjauan.</li>
                    <li>Menandai laporan yang perlu diteruskan.</li>
                </ul>
            </div>

            <div class="card">
                <span class="badge">Berikutnya</span>
                <h2>Daftar laporan segera hadir</h2>
                <p>Daftar laporan, filter, dan form peninjauan akan ditambahkan pada langkah berikutnya.</p>
            </div>
        </section>
    @elseif (auth()->user()->isCoordinator())
        <section class="role-guide">
            <div class="card">
                <span class="badge">Peran kamu</span>
                <h2>Koordinator Komunitas</h2>
                <p>Kamu mencatat penerusan informasi, menutup pemantauan, dan mengelola data pendukung aplikasi.</p>
            </div>

            <div class="card">
                <span class="badge">Yang bisa kamu lakukan</span>
                <ul class="check-list">
                    <li>Melihat rekap kondisi laporan.</li>
                    <li>Mencatat penerusan kepada pihak terkait.</li>
                    <li>Menutup pemantauan laporan.</li>
                    <li>Mengelola data sekolah dan distribusi.</li>
                </ul>
            </div>

            <div class="card">
                <span class="badge">Berikutnya</span>
                <h2>Rekap dan data induk segera hadir</h2>
                <p>Halaman rekap, data sekolah, dan data distribusi akan ditambahkan pada langkah berikutnya.</p>
            </div>
        </section>
    @endif
@endsection
