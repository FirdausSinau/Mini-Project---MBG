@extends('layouts.app')

@section('title', auth()->user()->isStaff() ? 'Semua Laporan' : 'Laporan Saya')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Laporan</p>
        <h1>{{ auth()->user()->isStaff() ? 'Semua laporan' : 'Laporan saya' }}</h1>
        <p>
            {{ auth()->user()->isStaff()
                ? 'Laporan yang masuk dari seluruh pelapor.'
                : 'Semua laporan yang kamu kirim.' }}
        </p>
    </section>

    @if (auth()->user()->isReporter())
        <div class="page-actions page-actions--top">
            <a href="{{ route('reports.create') }}" class="btn btn--primary">Buat laporan</a>
        </div>
    @endif

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Laporan</th>
                    <th>Sekolah</th>
                    <th>Distribusi</th>
                    <th>Kejadian</th>
                    <th>Pelapor</th>
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
                        <td>{{ $report->reporter->nama_lengkap }}</td>
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
                        <td colspan="7" class="empty-state">
                            Belum ada laporan.
                            @if (auth()->user()->isReporter())
                                <a href="{{ route('reports.create') }}">Buat laporan pertama</a>.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
