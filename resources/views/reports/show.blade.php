@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Laporan #{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</p>
        <h1>Detail laporan</h1>
        <p>
            <span class="status-badge {{ $report->status->badgeClass() }}">
                {{ $report->status->label() }}
            </span>
        </p>
    </section>

    <div class="detail-grid">
        <section class="card">
            <h2>Konteks kejadian</h2>
            <dl class="detail-list">
                <div>
                    <dt>Sekolah</dt>
                    <dd>{{ $report->distribution->school->nama }}</dd>
                </div>
                <div>
                    <dt>Distribusi</dt>
                    <dd>
                        {{ $report->distribution->tanggal->format('d M Y') }}
                        - {{ $report->distribution->menu ?? 'Menu belum dicatat' }}
                    </dd>
                </div>
                <div>
                    <dt>Waktu kejadian</dt>
                    <dd>{{ $report->incident_at->format('d M Y H:i') }}</dd>
                </div>
                <div>
                    <dt>Lokasi detail</dt>
                    <dd>{{ $report->location_detail ?? '-' }}</dd>
                </div>
            </dl>
        </section>

        <section class="card">
            <h2>Deskripsi</h2>
            <p class="report-description">{{ $report->description }}</p>
        </section>

        <section class="card">
            <h2>Bukti pendukung</h2>
            <p>Belum ada bukti. Upload bukti akan ditambahkan pada langkah berikutnya.</p>
        </section>

        <section class="card">
            <h2>Catatan peninjauan</h2>
            <p>Belum ada catatan. Peninjauan akan dilakukan oleh Pemantau.</p>
        </section>
    </div>

    <div class="page-actions">
        <a href="{{ route('reports.index') }}" class="btn btn--ghost">Kembali ke daftar</a>
    </div>
@endsection
