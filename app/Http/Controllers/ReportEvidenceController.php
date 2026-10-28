<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportEvidenceController extends Controller
{
    public function show(Report $report): StreamedResponse
    {
        Gate::authorize('view', $report);

        abort_unless(
            $report->evidence_path
            && Storage::disk('local')->exists($report->evidence_path),
            404
        );

        return Storage::disk('local')->response(
            $report->evidence_path,
            $report->evidence_original_name
        );
    }
}
