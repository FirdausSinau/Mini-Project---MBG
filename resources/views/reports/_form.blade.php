@php($report = $report ?? null)

<div class="form-field">
    <label for="school_id">Sekolah</label>
    <select
        id="school_id"
        name="school_id"
        required
        @error('school_id') aria-invalid="true" aria-describedby="school_id-error" @enderror
    >
        <option value="">Pilih sekolah</option>
        @foreach ($schools as $school)
            <option value="{{ $school->id }}" @selected(old('school_id', $report?->distribution?->school_id) == $school->id)>
                {{ $school->nama }}
            </option>
        @endforeach
    </select>
    @error('school_id')
        <p id="school_id-error" class="form-error">{{ $message }}</p>
    @enderror
</div>

<div class="form-field">
    <label for="distribution_id">Distribusi</label>
    <select
        id="distribution_id"
        name="distribution_id"
        required
        disabled
        @error('distribution_id') aria-invalid="true" aria-describedby="distribution_id-error" @enderror
    >
        <option value="">Pilih sekolah dulu</option>
    </select>
    <p id="distribution-status" class="form-help" aria-live="polite"></p>
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
        value="{{ old('incident_at', $report?->incident_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}"
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
        value="{{ old('location_detail', $report?->location_detail) }}"
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
    >{{ old('description', $report?->description) }}</textarea>
    <p class="form-help">Minimal 20 karakter. Jangan menulis nama siswa atau data pribadi.</p>
    @error('description')
        <p id="description-error" class="form-error">{{ $message }}</p>
    @enderror
</div>

@if ($report?->evidence_path)
    <div class="form-field form-field--full">
        <p class="form-help">
            Bukti saat ini:
            <a href="{{ route('reports.evidence', $report) }}" target="_blank" rel="noopener">
                {{ $report->evidence_original_name ?? 'Lihat bukti' }}
            </a>
        </p>
    </div>
@endif

<div class="form-field form-field--full">
    <label for="evidence">
        {{ $report ? 'Ganti bukti pendukung' : 'Bukti pendukung' }}
        <span class="optional">(opsional)</span>
    </label>
    <input
        id="evidence"
        name="evidence"
        type="file"
        accept=".jpg,.jpeg,.png,.pdf"
        @error('evidence') aria-invalid="true" aria-describedby="evidence-error" @enderror
    >
    <p class="form-help">
        JPG, JPEG, PNG, atau PDF. Maksimal 2 MB.
        @if ($report?->evidence_path)
            Kosongkan jika tidak ingin mengganti bukti.
        @endif
    </p>
    @error('evidence')
        <p id="evidence-error" class="form-error">{{ $message }}</p>
    @enderror
</div>

<div class="form-actions form-field--full">
    <a href="{{ $cancelUrl }}" class="btn btn--ghost">Batal</a>
    <button type="submit" class="btn btn--primary">{{ $submitLabel }}</button>
</div>
