@extends('layouts.app')

@section('title', 'Rekap Laporan')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Rekap</p>
        <h1>Rekap laporan</h1>
        <p>Gambaran kondisi laporan berdasarkan sekolah dan rentang tanggal kejadian.</p>
    </section>

    <section class="card filter-card">
        @if ($errors->any())
            <div class="alert alert--danger">{{ $errors->first() }}</div>
        @endif

        <form method="GET" action="{{ route('recap.index') }}" class="filter-bar">
            <select name="school_id" aria-label="Sekolah">
                <option value="">Semua sekolah</option>
                @foreach ($schoolOptions as $school)
                    <option value="{{ $school->id }}" @selected(request('school_id') == $school->id)>
                        {{ $school->nama }}
                    </option>
                @endforeach
            </select>

            <label for="from" class="filter-label">Dari</label>
            <input id="from" type="date" name="from" value="{{ request('from') }}">

            <label for="to" class="filter-label">Sampai</label>
            <input id="to" type="date" name="to" value="{{ request('to') }}">

            <button type="submit" class="btn btn--primary btn--sm">Terapkan</button>
            <a href="{{ route('recap.index') }}" class="btn btn--ghost btn--sm">Reset</a>
        </form>
    </section>

    <section class="recap-stats" aria-label="Ringkasan laporan">
        <article class="stat-card">
            <p class="stat-card__label">Total laporan</p>
            <p class="stat-card__value">{{ $total }}</p>
        </article>

        <article class="stat-card">
            <p class="stat-card__label">Sedang diproses</p>
            <p class="stat-card__value">{{ $inProcess }}</p>
        </article>

        <article class="stat-card">
            <p class="stat-card__label">Perlu diteruskan</p>
            <p class="stat-card__value">{{ $needReferral }}</p>
        </article>

        <article class="stat-card">
            <p class="stat-card__label">Sudah diteruskan</p>
            <p class="stat-card__value">{{ $referred }}</p>
        </article>

        <article class="stat-card">
            <p class="stat-card__label">Selesai</p>
            <p class="stat-card__value">{{ $finished }}</p>
        </article>
    </section>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Sekolah</th>
                    <th>Total</th>
                    <th>Diproses</th>
                    <th>Diteruskan</th>
                    <th>Selesai</th>
                    <th>Laporan terakhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($schools as $school)
                    <tr>
                        <td>{{ $school->nama }}</td>
                        <td>{{ $school->total_reports }}</td>
                        <td>{{ $school->in_process_reports }}</td>
                        <td>{{ $school->referred_reports }}</td>
                        <td>{{ $school->finished_reports }}</td>
                        <td>
                            {{ $school->last_incident_at
                                ? \Illuminate\Support\Carbon::parse($school->last_incident_at)->format('d M Y')
                                : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            Tidak ada laporan pada filter ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <p class="form-help recap-note">
        Kolom Diteruskan mencakup status Perlu Diteruskan dan Diteruskan.
    </p>
@endsection
