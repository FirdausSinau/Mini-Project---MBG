<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Http\Requests\StoreReportRequest;
use App\Models\Report;
use App\Models\School;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->isReporter(), 403);

        $reports = Report::with('distribution.school')
            ->where('user_id', $request->user()->id)
            ->latest('incident_at')
            ->get();

        return view('reports.index', compact('reports'));
    }

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
        $report = $request->user()->reports()->create([
            ...$request->validated(),
            'status' => ReportStatus::Baru,
        ]);

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'Laporan berhasil dikirim.');
    }

    public function show(Request $request, Report $report): View
    {
        abort_unless($request->user()->isReporter(), 403);
        abort_unless($report->user_id === $request->user()->id, 403);

        $report->load('distribution.school');

        return view('reports.show', compact('report'));
    }
}
