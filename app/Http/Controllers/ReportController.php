<?php

namespace App\Http\Controllers;

use App\Enums\ReferralParty;
use App\Enums\ReportStatus;
use App\Enums\ReviewDecision;
use App\Http\Requests\CloseReportRequest;
use App\Http\Requests\ReferReportRequest;
use App\Http\Requests\ReportFilterRequest;
use App\Http\Requests\ReviewReportRequest;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Report;
use App\Models\School;
use App\Services\ReportService;
use DomainException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(ReportFilterRequest $request): View
    {
        $user = $request->user();

        $filters = $request->validated();

        $reports = Report::with(['distribution.school', 'reporter'])
            ->when($user->isReporter(), fn ($query) => $query->where('user_id', $user->id))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['school_id'] ?? null, fn ($query, $schoolId) => $query->whereHas(
                'distribution',
                fn ($query) => $query->where('school_id', $schoolId)
            ))
            ->when($filters['q'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('description', 'like', "%{$search}%")
                        ->orWhereHas(
                            'distribution.school',
                            fn ($query) => $query->where('nama', 'like', "%{$search}%")
                        )
                        ->orWhereHas(
                            'reporter',
                            fn ($query) => $query->where('nama_lengkap', 'like', "%{$search}%")
                        );
                });
            })
            ->latest('incident_at')
            ->simplePaginate(10)
            ->withQueryString();

        $schools = School::orderBy('nama')->get();
        $statuses = ReportStatus::cases();

        return view('reports.index', compact('reports', 'schools', 'statuses'));
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

        $report->load(
            'distribution.school',
            'reporter',
            'reviewer',
            'referrer',
            'closer',
            'statusLogs.actor',
        );

        $relatedReports = collect();

        if (auth()->user()->isStaff()) {
            $relatedReports = Report::with('reporter')
                ->where('distribution_id', $report->distribution_id)
                ->where('id', '!=', $report->id)
                ->latest('incident_at')
                ->get();
        }

        $decisions = ReviewDecision::cases();
        $partyTypes = ReferralParty::cases();

        return view('reports.show', compact('report', 'relatedReports', 'decisions', 'partyTypes'));
    }

    public function review(
        ReviewReportRequest $request,
        Report $report,
        ReportService $service
    ): RedirectResponse {
        $data = $request->validated();

        try {
            $service->review(
                $report,
                ReviewDecision::from($data['decision']),
                $data['note'],
                $request->user(),
            );
        } catch (DomainException $exception) {
            return redirect()
                ->route('reports.show', $report)
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'Peninjauan berhasil disimpan.');
    }

    public function resubmit(Report $report, ReportService $service): RedirectResponse
    {
        Gate::authorize('resubmit', $report);

        try {
            $service->resubmit($report);
        } catch (DomainException $exception) {
            return redirect()
                ->route('reports.show', $report)
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'Laporan berhasil dikirim ulang.');
    }

    public function refer(
        ReferReportRequest $request,
        Report $report,
        ReportService $service
    ): RedirectResponse {
        $data = $request->validated();

        try {
            $service->refer(
                $report,
                ReferralParty::from($data['party_type']),
                $data['party_name'],
                $data['note'] ?? null,
                $request->user(),
            );
        } catch (DomainException $exception) {
            return redirect()
                ->route('reports.show', $report)
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'Penerusan berhasil dicatat.');
    }

    public function close(
        CloseReportRequest $request,
        Report $report,
        ReportService $service
    ): RedirectResponse {
        $data = $request->validated();

        try {
            $service->close(
                $report,
                $data['closure_note'] ?? null,
                $request->user(),
            );
        } catch (DomainException $exception) {
            return redirect()
                ->route('reports.show', $report)
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'Laporan berhasil ditutup.');
    }
}
