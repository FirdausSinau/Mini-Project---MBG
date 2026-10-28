@extends('layouts.app')

@section('title', 'Buat Laporan')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Laporan</p>
        <h1>Buat laporan kejadian</h1>
        <p>Pilih distribusi yang berkaitan, lalu jelaskan kejadian yang kamu ketahui.</p>
    </section>

    <section class="card form-card">
        <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="form form--grid">
            @csrf

            <div class="form-field form-field--full">
                <label for="distribution_id">Distribusi</label>
                <select
                    id="distribution_id"
                    name="distribution_id"
                    required
                    @error('distribution_id') aria-invalid="true" aria-describedby="distribution_id-error" @enderror
                >
                    <option value="">Pilih distribusi</option>

                    @foreach ($schools as $school)
                        @if ($school->distributions->isNotEmpty())
                            <optgroup label="{{ $school->nama }}">
                                @foreach ($school->distributions as $distribution)
                                    <option
                                        value="{{ $distribution->id }}"
                                        @selected(old('distribution_id') == $distribution->id)
                                    >
                                        {{ $distribution->tanggal->format('d M Y') }}
                                        - {{ $distribution->menu ?? 'Menu belum dicatat' }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    @endforeach
                </select>
                @error('distribution_id')
                    <p id="distribution_id-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="incident_at">Waktu kejadian</label>
                <input
                    id="incident_at"
                    name="incident_at"
                    type="datetime-local"
                    value="{{ old('incident_at', now()->format('Y-m-d\TH:i')) }}"
                    required
                    @error('incident_at') aria-invalid="true" aria-describedby="incident_at-error" @enderror
                >
                @error('incident_at')
                    <p id="incident_at-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="location_detail">Lokasi detail <span class="optional">(opsional)</span></label>
                <input
                    id="location_detail"
                    name="location_detail"
                    type="text"
                    value="{{ old('location_detail') }}"
                    maxlength="150"
                    placeholder="Contoh: Kelas 7A atau kantin"
                    @error('location_detail') aria-invalid="true" aria-describedby="location_detail-error" @enderror
                >
                @error('location_detail')
                    <p id="location_detail-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field form-field--full">
                <label for="description">Deskripsi kejadian</label>
                <textarea
                    id="description"
                    name="description"
                    rows="6"
                    required
                    minlength="20"
                    maxlength="2000"
                    placeholder="Jelaskan apa yang terjadi, kapan, dan siapa yang terdampak."
                    @error('description') aria-invalid="true" aria-describedby="description-error" @enderror
                >{{ old('description') }}</textarea>
                <p class="form-help">Minimal 20 karakter. Jangan menulis nama siswa atau data pribadi.</p>
                @error('description')
                    <p id="description-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field form-field--full">
                <label for="evidence">Bukti pendukung <span class="optional">(opsional)</span></label>
                <input
                    id="evidence"
                    name="evidence"
                    type="file"
                    accept=".jpg,.jpeg,.png,.pdf"
                    @error('evidence') aria-invalid="true" aria-describedby="evidence-error" @enderror
                >
                <p class="form-help">JPG, JPEG, PNG, atau PDF. Maksimal 2 MB.</p>
                @error('evidence')
                    <p id="evidence-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions form-field--full">
                <a href="{{ route('dashboard') }}" class="btn btn--ghost">Batal</a>
                <button type="submit" class="btn btn--primary">Kirim laporan</button>
            </div>
        </form>
    </section>
@endsection
