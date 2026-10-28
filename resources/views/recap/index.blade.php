@extends('layouts.app')

@section('title', 'Rekap Laporan')

@section('content')
    <section class="page-heading">
        <h1>Rekap laporan</h1>
        <p class="page-finding">
            @if ($needReferral > 0)
                {{ $needReferral }} laporan menunggu penerusan.
            @else
                Tidak ada laporan yang menunggu penerusan.
            @endif
        </p>
    </section>

    <section class="card filter-card">
        @if ($errors->any())
            <div class="alert alert--danger">{{ $errors->first() }}</div>
        @endif

        <form method="GET" action="{{ route('recap.index') }}" class="filter-bar">
            <div class="filter-field filter-field--wide">
                <label for="school_id">Sekolah</label>
                <select id="school_id" name="school_id">
                    <option value="">Semua sekolah</option>
                    @foreach ($schoolOptions as $school)
                        <option value="{{ $school->id }}" @selected(request('school_id') == $school->id)>
                            {{ $school->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-field">
                <label for="from">Dari</label>
                <input id="from" type="date" name="from" value="{{ request('from') }}">
            </div>

            <div class="filter-field">
                <label for="to">Sampai</label>
                <input id="to" type="date" name="to" value="{{ request('to') }}">
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn--primary btn--sm">Terapkan</button>
                <a href="{{ route('recap.index') }}" class="btn btn--ghost btn--sm">Reset</a>
            </div>
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
                    <th class="num">Total</th>
                    <th class="num">Diproses</th>
                    <th class="num">Diteruskan</th>
                    <th class="num">Selesai</th>
                    <th class="num">Laporan terakhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($schools as $school)
                    <tr>
                        <td>{{ $school->nama }}</td>
                        <td class="num">{{ $school->total_reports }}</td>
                        <td class="num">{{ $school->in_process_reports }}</td>
                        <td class="num">{{ $school->referred_reports }}</td>
                        <td class="num">{{ $school->finished_reports }}</td>
                        <td class="num">
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
