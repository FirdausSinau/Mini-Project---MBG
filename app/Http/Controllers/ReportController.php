<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Report;
use App\Models\School;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $reports = Report::with(['distribution.school', 'reporter'])
            ->when($user->isReporter(), fn ($query) => $query->where('user_id', $user->id))
            ->latest('incident_at')
            ->get();

        return view('reports.index', compact('reports'));
    }

    public function create(): View
    {
        $schools = School::orderBy('nama')->get();

        return view('reports.create', compact('schools'));
    }

    public function store(StoreReportRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['evidence'], $data['school_id']);

        if ($request->hasFile('evidence')) {
            $data['evidence_path'] = $request->file('evidence')
                ->store('evidence', 'local');

            $data['evidence_original_name'] = $request->file('evidence')
                ->getClientOriginalName();
        }

        $report = $request->user()->reports()->create([
            ...$data,
            'status' => ReportStatus::Baru,
        ]);

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'Laporan berhasil dikirim.');
    }

    public function edit(Report $report): View
    {
        Gate::authorize('update', $report);

        $schools = School::orderBy('nama')->get();
        $report->load('distribution.school');

        return view('reports.edit', compact('report', 'schools'));
    }

    public function update(UpdateReportRequest $request, Report $report): RedirectResponse
    {
        Gate::authorize('update', $report);

        $data = $request->validated();
        unset($data['evidence'], $data['school_id']);

        $oldEvidencePath = null;

        if ($request->hasFile('evidence')) {
            $oldEvidencePath = $report->evidence_path;

            $data['evidence_path'] = $request->file('evidence')
                ->store('evidence', 'local');

            $data['evidence_original_name'] = $request->file('evidence')
                ->getClientOriginalName();
        }

        $report->update($data);

        if ($oldEvidencePath) {
            Storage::disk('local')->delete($oldEvidencePath);
        }

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function show(Report $report): View
    {
        Gate::authorize('view', $report);

        $report->load('distribution.school', 'reporter');

        $relatedReports = collect();

        if (auth()->user()->isStaff()) {
            $relatedReports = Report::with('reporter')
                ->where('distribution_id', $report->distribution_id)
                ->where('id', '!=', $report->id)
                ->latest('incident_at')
                ->get();
        }

        return view('reports.show', compact('report', 'relatedReports'));
    }
}
