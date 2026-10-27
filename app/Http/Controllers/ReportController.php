<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Http\Requests\StoreReportRequest;
use App\Models\School;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ReportController extends Controller
{
    public function create(): View
    {
        $schools = School::with([
            'distributions' => fn ($query) => $query->orderByDesc('tanggal'),
        ])
            ->orderBy('nama')
            ->get();

        return view('reports.create', compact('schools'));
    }

    public function store(StoreReportRequest $request): RedirectResponse
    {
        $request->user()->reports()->create([
            ...$request->validated(),
            'status' => ReportStatus::Baru,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Laporan berhasil dikirim.');
    }
}
