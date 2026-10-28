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
                <span class="badge">Mulai</span>
                <h2>Buat laporan</h2>
                <p>Laporkan kejadian dengan konteks sekolah dan distribusi.</p>
                <div class="card-actions">
                    <a href="{{ route('reports.create') }}" class="btn btn--primary">Buat laporan</a>
                    <a href="{{ route('reports.index') }}" class="btn btn--ghost">Laporan saya</a>
                </div>
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
                <span class="badge">Mulai</span>
                <h2>Tinjau laporan</h2>
                <p>Lihat laporan yang masuk dan periksa kelengkapannya.</p>
                <div class="card-actions">
                    <a href="{{ route('reports.index') }}" class="btn btn--primary">Lihat laporan</a>
                    <a href="{{ route('recap.index') }}" class="btn btn--ghost">Rekap</a>
                </div>
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
                <span class="badge">Mulai</span>
                <h2>Pantau laporan dan data</h2>
                <p>Lihat laporan yang masuk, lalu kelola data sekolah dan distribusi.</p>
                <div class="card-actions">
                    <a href="{{ route('reports.index') }}" class="btn btn--primary">Lihat laporan</a>
                    <a href="{{ route('recap.index') }}" class="btn btn--ghost">Rekap</a>
                    <a href="{{ route('master-data.index') }}" class="btn btn--ghost">Data Induk</a>
                </div>
            </div>
        </section>
    @endif
@endsection
