<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Http\Requests\RecapFilterRequest;
use App\Models\Report;
use App\Models\School;
use Illuminate\Contracts\View\View;

class RecapController extends Controller
{
    public function index(RecapFilterRequest $request): View
    {
        $filters = $request->validated();
        $schoolId = $filters['school_id'] ?? null;
        $from = $filters['from'] ?? null;
        $to = $filters['to'] ?? null;

        $inProcessStatuses = [
            ReportStatus::Baru->value,
            ReportStatus::Ditinjau->value,
            ReportStatus::PerluPerbaikan->value,
        ];

        $referralStatuses = [
            ReportStatus::PerluDiteruskan->value,
            ReportStatus::Diteruskan->value,
        ];

        $base = Report::query()
            ->when($schoolId, fn ($query) => $query->whereHas(
                'distribution',
                fn ($query) => $query->where('school_id', $schoolId)
            ))
            ->when($from, fn ($query) => $query->whereDate('incident_at', '>=', $from))
            ->when($to, fn ($query) => $query->whereDate('incident_at', '<=', $to));

        $total = (clone $base)->count();
        $inProcess = (clone $base)->whereIn('status', $inProcessStatuses)->count();
        $needReferral = (clone $base)
            ->where('status', ReportStatus::PerluDiteruskan->value)
            ->count();
        $referred = (clone $base)
            ->where('status', ReportStatus::Diteruskan->value)
            ->count();
        $finished = (clone $base)
            ->where('status', ReportStatus::Selesai->value)
            ->count();

        $dateFilters = function ($query) use ($from, $to) {
            return $query
                ->when($from, fn ($query) => $query->whereDate('incident_at', '>=', $from))
                ->when($to, fn ($query) => $query->whereDate('incident_at', '<=', $to));
        };

        $schools = School::query()
            ->when($schoolId, fn ($query) => $query->whereKey($schoolId))
            ->withCount([
                'reports as total_reports' => fn ($query) => $dateFilters($query),
                'reports as in_process_reports' => fn ($query) => $dateFilters($query)
                    ->whereIn('status', $inProcessStatuses),
                'reports as referred_reports' => fn ($query) => $dateFilters($query)
                    ->whereIn('status', $referralStatuses),
                'reports as finished_reports' => fn ($query) => $dateFilters($query)
                    ->where('status', ReportStatus::Selesai->value),
            ])
            ->withMax(
                ['reports as last_incident_at' => fn ($query) => $dateFilters($query)],
                'incident_at'
            )
            ->orderBy('nama')
            ->get()
            ->filter(fn ($school) => $school->total_reports > 0)
            ->values();

        $schoolOptions = School::orderBy('nama')->get();

        return view('recap.index', compact(
            'total',
            'inProcess',
            'needReferral',
            'referred',
            'finished',
            'schools',
            'schoolOptions',
        ));
    }
}
