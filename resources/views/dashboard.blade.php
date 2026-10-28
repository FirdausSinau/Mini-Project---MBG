@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="page-heading">
        <h1>Dashboard</h1>
        <p>Selamat datang, {{ auth()->user()->nama_lengkap }}. Kamu masuk sebagai <strong>{{ auth()->user()->roleLabel() }}</strong>. Halaman ini menjadi titik awal pekerjaanmu.</p>
    </section>

    @if (auth()->user()->isReporter())
        <section class="card role-guide-card">
            <div class="role-guide-card__intro">
                <h2>Pelapor</h2>
                <p>Kamu dapat membuat laporan kejadian, menyertakan bukti, dan memantau status laporan milikmu sendiri.</p>
                <div class="card-actions">
                    <a href="{{ route('reports.create') }}" class="btn btn--primary">Buat laporan</a>
                    <a href="{{ route('reports.index') }}" class="btn btn--ghost">Laporan saya</a>
                </div>
            </div>

            <div class="role-guide-card__body">
                <h3>Yang bisa kamu lakukan</h3>
                <ul class="check-list">
                    <li>Membuat laporan dengan konteks sekolah dan distribusi.</li>
                    <li>Mengunggah satu bukti pendukung.</li>
                    <li>Memperbaiki laporan selama masih diizinkan.</li>
                    <li>Melihat perkembangan laporan sendiri.</li>
                </ul>
            </div>
        </section>
    @elseif (auth()->user()->isReviewer())
        <section class="card role-guide-card">
            <div class="role-guide-card__intro">
                <h2>Pemantau Komunitas</h2>
                <p>Kamu meninjau kelengkapan laporan, melihat laporan terkait, dan menandai laporan yang perlu diteruskan.</p>
                <div class="card-actions">
                    <a href="{{ route('reports.index') }}" class="btn btn--primary">Lihat laporan</a>
                    <a href="{{ route('recap.index') }}" class="btn btn--ghost">Rekap</a>
                </div>
            </div>

            <div class="role-guide-card__body">
                <h3>Yang bisa kamu lakukan</h3>
                <ul class="check-list">
                    <li>Melihat semua laporan yang masuk.</li>
                    <li>Melihat laporan lain pada distribusi yang sama.</li>
                    <li>Memberikan catatan peninjauan.</li>
                    <li>Menandai laporan yang perlu diteruskan.</li>
                </ul>
            </div>
        </section>
    @elseif (auth()->user()->isCoordinator())
        <section class="card role-guide-card">
            <div class="role-guide-card__intro">
                <h2>Koordinator Komunitas</h2>
                <p>Kamu mencatat penerusan informasi, menutup pemantauan, dan mengelola data pendukung aplikasi.</p>
                <div class="card-actions">
                    <a href="{{ route('reports.index') }}" class="btn btn--primary">Lihat laporan</a>
                    <a href="{{ route('recap.index') }}" class="btn btn--ghost">Rekap</a>
                    <a href="{{ route('master-data.index') }}" class="btn btn--ghost">Data Induk</a>
                </div>
            </div>

            <div class="role-guide-card__body">
                <h3>Yang bisa kamu lakukan</h3>
                <ul class="check-list">
                    <li>Melihat rekap kondisi laporan.</li>
                    <li>Mencatat penerusan kepada pihak terkait.</li>
                    <li>Menutup pemantauan laporan.</li>
                    <li>Mengelola data sekolah dan distribusi.</li>
                </ul>
            </div>
        </section>
    @endif
@endsection
