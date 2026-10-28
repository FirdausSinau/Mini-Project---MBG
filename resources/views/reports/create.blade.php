@extends('layouts.app')

@section('title', 'Buat Laporan')

@section('content')
    <section class="page-heading">
        <h1>Buat laporan kejadian</h1>
        <p>Pilih distribusi yang berkaitan, lalu jelaskan kejadian yang kamu ketahui.</p>
    </section>

    <section class="card form-card">
        <form method="POST" action="{{ route('reports.store') }}"
              enctype="multipart/form-data"
              class="form form--grid"
              data-report-form
              data-distributions-url-template="{{ route('schools.distributions.index', ['school' => '__SCHOOL__']) }}"
              data-selected-distribution="{{ old('distribution_id') }}">
            @csrf

            @include('reports._form', [
                'schools' => $schools,
                'report' => null,
                'submitLabel' => 'Kirim laporan',
                'cancelUrl' => route('dashboard'),
            ])
        </form>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/report-form.js') }}" defer></script>
@endpush
