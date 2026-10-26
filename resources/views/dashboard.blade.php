@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Dashboard</p>
        <h1>Selamat datang, {{ auth()->user()->nama_lengkap }}</h1>
        <p>Kamu masuk sebagai <strong>{{ auth()->user()->roleLabel() }}</strong>.</p>
    </section>

    <section class="card">
        <h2>Fitur laporan segera hadir</h2>
        <p>Pada langkah berikutnya, halaman ini akan menampilkan ringkasan laporan dan navigasi sesuai role.</p>
    </section>
@endsection
