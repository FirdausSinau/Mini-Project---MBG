@extends('layouts.app')

@section('title', 'Laporan Saya')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Laporan</p>
        <h1>Laporan saya</h1>
        <p>Semua laporan yang kamu kirim.</p>
    </section>

    <div class="page-actions page-actions--top">
        <a href="{{ route('reports.create') }}" class="btn btn--primary">Buat laporan</a>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Laporan</th>
                    <th>Sekolah</th>
                    <th>Distribusi</th>
                    <th>Kejadian</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reports as $report)
                    <tr>
                        <td>#{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $report->distribution->school->nama }}</td>
                        <td>{{ $report->distribution->tanggal->format('d M Y') }}</td>
                        <td>{{ $report->incident_at->format('d M Y H:i') }}</td>
                        <td>
                            <span class="status-badge {{ $report->status->badgeClass() }}">
                                {{ $report->status->label() }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('reports.show', $report) }}" class="btn btn--ghost btn--sm">
                                Lihat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            Belum ada laporan.
                            <a href="{{ route('reports.create') }}">Buat laporan pertama</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
