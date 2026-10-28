@extends('layouts.app')

@section('title', 'Edit Distribusi')

@section('content')
    <section class="page-heading">
        <h1>Edit distribusi</h1>
        <p>Perbarui data distribusi.</p>
    </section>

    <section class="card form-card">
        <form method="POST" action="{{ route('distributions.update', $distribution) }}" class="form form--grid">
            @csrf
            @method('PATCH')

            <div class="form-field form-field--full">
                <label for="school_id">Sekolah</label>
                <select
                    id="school_id"
                    name="school_id"
                    required
                    @error('school_id') aria-invalid="true" aria-describedby="school_id-error" @enderror
                >
                    <option value="">Pilih sekolah</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}" @selected(old('school_id', $distribution->school_id) == $school->id)>
                            {{ $school->nama }}
                        </option>
                    @endforeach
                </select>
                @error('school_id')
                    <p id="school_id-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="tanggal">Tanggal</label>
                <input
                    id="tanggal"
                    name="tanggal"
                    type="date"
                    value="{{ old('tanggal', $distribution->tanggal->format('Y-m-d')) }}"
                    required
                    @error('tanggal') aria-invalid="true" aria-describedby="tanggal-error" @enderror
                >
                @error('tanggal')
                    <p id="tanggal-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="menu">Menu</label>
                <input
                    id="menu"
                    name="menu"
                    type="text"
                    value="{{ old('menu', $distribution->menu) }}"
                    maxlength="255"
                    @error('menu') aria-invalid="true" aria-describedby="menu-error" @enderror
                >
                @error('menu')
                    <p id="menu-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions form-field--full">
                <a href="{{ route('master-data.index') }}" class="btn btn--ghost">Batal</a>
                <button type="submit" class="btn btn--primary">Simpan perubahan</button>
            </div>
        </form>
    </section>
@endsection
