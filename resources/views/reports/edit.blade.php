@extends('layouts.app')

@section('title', 'Edit Laporan')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Laporan #{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</p>
        <h1>Edit laporan</h1>
        <p>
            <span class="status-badge {{ $report->status->badgeClass() }}">
                {{ $report->status->label() }}
            </span>
        </p>
    </section>

    <section class="card form-card">
        <form method="POST"
              action="{{ route('reports.update', $report) }}"
              enctype="multipart/form-data"
              class="form form--grid"
              data-report-form
              data-distributions-url-template="{{ route('schools.distributions.index', ['school' => '__SCHOOL__']) }}"
              data-selected-distribution="{{ old('distribution_id', $report->distribution_id) }}">
            @csrf
            @method('PATCH')

            @include('reports._form', [
                'schools' => $schools,
                'report' => $report,
                'submitLabel' => 'Simpan perubahan',
                'cancelUrl' => route('reports.show', $report),
            ])
        </form>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/report-form.js') }}" defer></script>
@endpush
