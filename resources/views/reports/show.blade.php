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

        @php
            $isRepairNote = $report->review_note && (
                $report->status === \App\Enums\ReportStatus::PerluPerbaikan
                || ($report->status === \App\Enums\ReportStatus::Baru && $report->reviewed_at)
            );
        @endphp

        <section class="card">
            <h2>{{ $isRepairNote ? 'Catatan perbaikan terakhir' : 'Catatan peninjauan' }}</h2>

            @if ($report->review_note)
                <p class="report-description">{{ $report->review_note }}</p>
                <p class="form-help">
                    Ditinjau oleh {{ $report->reviewer?->nama_lengkap ?? 'Pemantau' }}
                    pada {{ $report->reviewed_at?->format('d M Y H:i') }}
                </p>
                @if ($isRepairNote)
                    <p class="form-help">
                        Catatan ini menjadi alasan perbaikan terakhir. Catatan akan diperbarui saat peninjauan berikutnya.
                    </p>
                @endif
            @else
                <p>Belum ada catatan. Peninjauan akan dilakukan oleh Pemantau.</p>
            @endif
        </section>

        @if ($report->referred_at)
            <section class="card">
                <h2>Penerusan</h2>
                <dl class="detail-list">
                    <div>
                        <dt>Pihak tujuan</dt>
                        <dd>
                            {{ $report->referral_party_type?->label() }}
                            - {{ $report->referral_party_name }}
                        </dd>
                    </div>
                    @if ($report->referral_note)
                        <div>
                            <dt>Catatan penerusan</dt>
                            <dd class="report-description">{{ $report->referral_note }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt>Dicatat oleh</dt>
                        <dd>
                            {{ $report->referrer?->nama_lengkap ?? 'Koordinator' }}
                            pada {{ $report->referred_at->format('d M Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </section>
        @elseif ($report->status === \App\Enums\ReportStatus::PerluDiteruskan)
            <section class="card">
                <h2>Penerusan</h2>
                <p>Laporan ditandai perlu diteruskan. Menunggu Koordinator mencatat penerusan.</p>
            </section>
        @endif

        @if ($report->closed_at)
            <section class="card">
                <h2>Penutupan</h2>
                <dl class="detail-list">
                    <div>
                        <dt>Catatan penutupan</dt>
                        <dd class="report-description">
                            {{ $report->closure_note ?? 'Tidak ada catatan.' }}
                        </dd>
                    </div>
                    <div>
                        <dt>Ditutup oleh</dt>
                        <dd>
                            {{ $report->closer?->nama_lengkap ?? 'Koordinator' }}
                            pada {{ $report->closed_at->format('d M Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </section>
        @endif

        <section class="card detail-grid__full">
            <h2>Timeline status</h2>

            @if ($report->statusLogs->isEmpty())
                <p>Belum ada perubahan status.</p>
            @else
                <ol class="timeline">
                    @foreach ($report->statusLogs as $log)
                        <li class="timeline__item">
                            <span class="timeline__marker" aria-hidden="true"></span>

                            <div class="timeline__content">
                                <div class="timeline__transition">
                                    <span class="status-badge {{ $log->from_status->badgeClass() }}">
                                        {{ $log->from_status->label() }}
                                    </span>
                                    <span class="timeline__arrow" aria-hidden="true">→</span>
                                    <span class="status-badge {{ $log->to_status->badgeClass() }}">
                                        {{ $log->to_status->label() }}
                                    </span>
                                </div>

                                <p class="timeline__meta">
                                    {{ $log->actor?->nama_lengkap ?? 'Sistem' }}
                                    pada {{ $log->created_at?->format('d M Y H:i') }}
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif
        </section>

        @can('refer', $report)
            @if ($report->status === \App\Enums\ReportStatus::PerluDiteruskan)
                <section class="card detail-grid__full">
                    <h2>Form penerusan</h2>

                    <form method="POST"
                          action="{{ route('reports.referral', $report) }}"
                          class="form form--grid">
                        @csrf

                        <div class="form-field">
                            <label for="party_type">Jenis pihak</label>
                            <select
                                id="party_type"
                                name="party_type"
                                required
                                @error('party_type') aria-invalid="true" aria-describedby="party_type-error" @enderror
                            >
                                <option value="">Pilih jenis pihak</option>
                                @foreach ($partyTypes as $partyType)
                                    <option value="{{ $partyType->value }}" @selected(old('party_type') === $partyType->value)>
                                        {{ $partyType->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('party_type')
                                <p id="party_type-error" class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label for="party_name">Nama pihak</label>
                            <input
                                id="party_name"
                                name="party_name"
                                type="text"
                                value="{{ old('party_name') }}"
                                maxlength="150"
                                required
                                @error('party_name') aria-invalid="true" aria-describedby="party_name-error" @enderror
                            >
                            @error('party_name')
                                <p id="party_name-error" class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-field form-field--full">
                            <label for="referral_note">
                                Catatan penerusan <span class="optional">(opsional)</span>
                            </label>
                            <textarea
                                id="referral_note"
                                name="note"
                                rows="4"
                                maxlength="1000"
                                @error('note') aria-invalid="true" aria-describedby="referral_note-error" @enderror
                            >{{ old('note') }}</textarea>
                            @error('note')
                                <p id="referral_note-error" class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-actions form-field--full">
                            <button type="submit" class="btn btn--primary">Simpan penerusan</button>
                        </div>
                    </form>
                </section>
            @endif
        @endcan

        @can('close', $report)
            @if (in_array($report->status, [
                \App\Enums\ReportStatus::Ditinjau,
                \App\Enums\ReportStatus::Diteruskan,
            ], true))
                <section class="card detail-grid__full">
                    <h2>Form penutupan</h2>

                    <form method="POST"
                          action="{{ route('reports.close', $report) }}"
                          class="form">
                        @csrf

                        <div class="form-field">
                            <label for="closure_note">
                                Catatan penutupan <span class="optional">(opsional)</span>
                            </label>
                            <textarea
                                id="closure_note"
                                name="closure_note"
                                rows="4"
                                maxlength="1000"
                                @error('closure_note') aria-invalid="true" aria-describedby="closure_note-error" @enderror
                            >{{ old('closure_note') }}</textarea>
                            @error('closure_note')
                                <p id="closure_note-error" class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn--primary">Tutup laporan</button>
                        </div>
                    </form>
                </section>
            @endif
        @endcan

        @can('review', $report)
            @if ($report->status === \App\Enums\ReportStatus::Baru)
                <section class="card detail-grid__full">
                    <h2>Form peninjauan</h2>

                    <form method="POST"
                          action="{{ route('reports.review', $report) }}"
                          class="form form--grid">
                        @csrf

                        <div class="form-field">
                            <label for="decision">Keputusan</label>
                            <select
                                id="decision"
                                name="decision"
                                required
                                @error('decision') aria-invalid="true" aria-describedby="decision-error" @enderror
                            >
                                <option value="">Pilih keputusan</option>
                                @foreach ($decisions as $decision)
                                    <option value="{{ $decision->value }}" @selected(old('decision') === $decision->value)>
                                        {{ $decision->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('decision')
                                <p id="decision-error" class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-field form-field--full">
                            <label for="note">Catatan peninjauan</label>
                            <textarea
                                id="note"
                                name="note"
                                rows="5"
                                required
                                minlength="10"
                                maxlength="1000"
                                placeholder="Jelaskan alasan keputusan ini."
                                @error('note') aria-invalid="true" aria-describedby="note-error" @enderror
                            >{{ old('note') }}</textarea>
                            <p class="form-help">Minimal 10 karakter. Catatan akan dibaca Pelapor.</p>
                            @error('note')
                                <p id="note-error" class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-actions form-field--full">
                            <button type="submit" class="btn btn--primary">Simpan peninjauan</button>
                        </div>
                    </form>
                </section>
            @endif
        @endcan
    </div>

    <div class="page-actions">
        <a href="{{ route('reports.index') }}" class="btn btn--ghost">Kembali ke daftar</a>

        @can('update', $report)
            <a href="{{ route('reports.edit', $report) }}" class="btn btn--primary">Edit laporan</a>
        @endcan

        @can('resubmit', $report)
            <form method="POST" action="{{ route('reports.resubmit', $report) }}">
                @csrf
                <button type="submit" class="btn btn--primary">Kirim ulang laporan</button>
            </form>
        @endcan
    </div>
@endsection
