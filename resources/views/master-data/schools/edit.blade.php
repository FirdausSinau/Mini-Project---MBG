@extends('layouts.app')

@section('title', 'Edit Sekolah')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Data Induk</p>
        <h1>Edit sekolah</h1>
        <p>Perbarui data sekolah.</p>
    </section>

    <section class="card form-card">
        <form method="POST" action="{{ route('schools.update', $school) }}" class="form form--grid">
            @csrf
            @method('PATCH')

            <div class="form-field form-field--full">
                <label for="nama">Nama sekolah</label>
                <input
                    id="nama"
                    name="nama"
                    type="text"
                    value="{{ old('nama', $school->nama) }}"
                    required
                    maxlength="150"
                    @error('nama') aria-invalid="true" aria-describedby="nama-error" @enderror
                >
                @error('nama')
                    <p id="nama-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="alamat">Alamat</label>
                <input
                    id="alamat"
                    name="alamat"
                    type="text"
                    value="{{ old('alamat', $school->alamat) }}"
                    maxlength="255"
                    @error('alamat') aria-invalid="true" aria-describedby="alamat-error" @enderror
                >
                @error('alamat')
                    <p id="alamat-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="kota">Kota</label>
                <input
                    id="kota"
                    name="kota"
                    type="text"
                    value="{{ old('kota', $school->kota) }}"
                    maxlength="100"
                    @error('kota') aria-invalid="true" aria-describedby="kota-error" @enderror
                >
                @error('kota')
                    <p id="kota-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions form-field--full">
                <a href="{{ route('master-data.index') }}" class="btn btn--ghost">Batal</a>
                <button type="submit" class="btn btn--primary">Simpan perubahan</button>
            </div>
        </form>
    </section>
@endsection
