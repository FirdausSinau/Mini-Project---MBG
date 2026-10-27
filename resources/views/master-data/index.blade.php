@extends('layouts.app')

@section('title', 'Data Induk')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Data Induk</p>
        <h1>Kelola sekolah dan distribusi</h1>
        <p>Tambahkan data yang dibutuhkan untuk konteks laporan.</p>
    </section>

    <div class="master-grid">
        <section class="card">
            <div class="section-heading">
                <h2>Sekolah</h2>
                <span class="badge">{{ $schools->count() }} sekolah</span>
            </div>

            <form method="POST" action="{{ route('schools.store') }}" class="form form--grid">
                @csrf

                <div class="form-field form-field--full">
                    <label for="nama">Nama sekolah</label>
                    <input
                        id="nama"
                        name="nama"
                        type="text"
                        value="{{ old('nama') }}"
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
                        value="{{ old('alamat') }}"
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
                        value="{{ old('kota') }}"
                        maxlength="100"
                        @error('kota') aria-invalid="true" aria-describedby="kota-error" @enderror
                    >
                    @error('kota')
                        <p id="kota-error" class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions form-field--full">
                    <button type="submit" class="btn btn--primary">Tambah sekolah</button>
                </div>
            </form>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kota</th>
                            <th>Distribusi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schools as $school)
                            <tr>
                                <td>{{ $school->nama }}</td>
                                <td>{{ $school->kota ?? '-' }}</td>
                                <td>{{ $school->distributions_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="empty-state">Belum ada sekolah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card">
            <div class="section-heading">
                <h2>Distribusi</h2>
                <span class="badge">{{ $distributions->count() }} distribusi</span>
            </div>

            <form method="POST" action="{{ route('distributions.store') }}" class="form form--grid">
                @csrf

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
                            <option value="{{ $school->id }}" @selected(old('school_id') == $school->id)>
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
                        value="{{ old('tanggal') }}"
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
                        value="{{ old('menu') }}"
                        maxlength="255"
                        @error('menu') aria-invalid="true" aria-describedby="menu-error" @enderror
                    >
                    @error('menu')
                        <p id="menu-error" class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions form-field--full">
                    <button type="submit" class="btn btn--primary">Tambah distribusi</button>
                </div>
            </form>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Sekolah</th>
                            <th>Menu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($distributions as $distribution)
                            <tr>
                                <td>{{ $distribution->tanggal->format('d M Y') }}</td>
                                <td>{{ $distribution->school->nama }}</td>
                                <td>{{ $distribution->menu ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="empty-state">Belum ada distribusi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
