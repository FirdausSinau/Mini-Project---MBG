@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    <div class="auth-card">
        <div class="auth-card__header">
            <span class="badge">Lapor MBG</span>
            <h1>Masuk ke akun</h1>
            <p>Gunakan akun yang sudah terdaftar untuk melaporkan atau meninjau laporan.</p>
        </div>

        @error('login')
            <div class="alert alert--danger">{{ $message }}</div>
        @enderror

        <form method="POST" action="{{ route('login') }}" class="form">
            @csrf

            <div class="form-field">
                <label for="username">Username</label>
                <input
                    id="username"
                    name="username"
                    type="text"
                    value="{{ old('username') }}"
                    required
                    autofocus
                    autocomplete="username"
                    @error('username') aria-invalid="true" aria-describedby="username-error" @enderror
                >
                @error('username')
                    <p id="username-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="password">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                >
                @error('password')
                    <p id="password-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn--primary btn--block">Masuk</button>
        </form>

        <details class="demo-accounts">
            <summary>Akun demo</summary>
            <ul>
                <li><strong>Giorno</strong> / rahasia123 <span>Pelapor</span></li>
                <li><strong>Jotaro</strong> / rahasia123 <span>Pelapor</span></li>
                <li><strong>Dramok</strong> / rahasia123 <span>Pemantau</span></li>
                <li><strong>Perrell</strong> / rahasia123 <span>Koordinator</span></li>
            </ul>
        </details>
    </div>
@endsection
