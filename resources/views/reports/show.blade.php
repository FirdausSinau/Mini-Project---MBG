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
                    <dt>Pelapor</dt>
                    <dd>{{ $report->reporter->nama_lengkap }}</dd>
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

            @if ($report->evidence_path)
                <p>
                    <a href="{{ route('reports.evidence', $report) }}" target="_blank" rel="noopener">
                        {{ $report->evidence_original_name ?? 'Lihat bukti' }}
                    </a>
                </p>
                <p class="form-help">
                    File disimpan secara privat. Hanya pemilik dan petugas berwenang yang bisa membuka.
                </p>
            @else
                <p>Belum ada bukti pendukung.</p>
            @endif
        </section>

        @if (auth()->user()->isStaff())
            <section class="card detail-grid__full">
                <h2>Laporan lain pada distribusi ini</h2>

                @if ($relatedReports->isEmpty())
                    <p>Tidak ada laporan lain pada distribusi ini.</p>
                @else
                    <ul class="related-list">
                        @foreach ($relatedReports as $related)
                            <li>
                                <a href="{{ route('reports.show', $related) }}">
                                    #{{ str_pad($related->id, 4, '0', STR_PAD_LEFT) }}
                                </a>
                                <span class="status-badge {{ $related->status->badgeClass() }}">
                                    {{ $related->status->label() }}
                                </span>
                                <span class="form-help">
                                    {{ $related->incident_at->format('d M Y H:i') }}
                                </span>
                                <span class="form-help">
                                    {{ $related->reporter->nama_lengkap }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        @endif

        <section class="card">
            <h2>Catatan peninjauan</h2>
            <p>Belum ada catatan. Peninjauan akan dilakukan oleh Pemantau.</p>
        </section>
    </div>

    <div class="page-actions">
        <a href="{{ route('reports.index') }}" class="btn btn--ghost">Kembali ke daftar</a>

        @can('update', $report)
            <a href="{{ route('reports.edit', $report) }}" class="btn btn--primary">Edit laporan</a>
        @endcan
    </div>
@endsection
