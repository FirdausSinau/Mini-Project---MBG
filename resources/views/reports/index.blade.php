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

    <section class="card filter-card">
        @if ($errors->any())
            <div class="alert alert--danger">{{ $errors->first() }}</div>
        @endif

        <form method="GET" action="{{ route('reports.index') }}" class="filter-bar">
            <input
                type="search"
                name="q"
                value="{{ request('q') }}"
                placeholder="{{ auth()->user()->isStaff()
                    ? 'Cari sekolah, pelapor, atau deskripsi'
                    : 'Cari sekolah atau deskripsi' }}"
            >

            <select name="status">
                <option value="">Semua status</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>

            <select name="school_id">
                <option value="">Semua sekolah</option>
                @foreach ($schools as $school)
                    <option value="{{ $school->id }}" @selected(request('school_id') == $school->id)>
                        {{ $school->nama }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn--primary btn--sm">Terapkan</button>
            <a href="{{ route('reports.index') }}" class="btn btn--ghost btn--sm">Reset</a>
        </form>
    </section>

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
                            @if (request()->hasAny(['q', 'status', 'school_id']))
                                Tidak ada laporan yang cocok dengan filter.
                                <a href="{{ route('reports.index') }}">Reset filter</a>.
                            @else
                                Belum ada laporan.
                                @if (auth()->user()->isReporter())
                                    <a href="{{ route('reports.create') }}">Buat laporan pertama</a>.
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($reports->hasPages())
        <div class="pagination">
            @if ($reports->onFirstPage())
                <span class="pagination__link is-disabled">Sebelumnya</span>
            @else
                <a href="{{ $reports->previousPageUrl() }}" class="pagination__link">Sebelumnya</a>
            @endif

            <span class="pagination__info">Halaman {{ $reports->currentPage() }}</span>

            @if ($reports->hasMorePages())
                <a href="{{ $reports->nextPageUrl() }}" class="pagination__link">Berikutnya</a>
            @else
                <span class="pagination__link is-disabled">Berikutnya</span>
            @endif
        </div>
    @endif
@endsection
