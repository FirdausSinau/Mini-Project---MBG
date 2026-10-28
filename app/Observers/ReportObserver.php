<?php

namespace App\Observers;

use App\Models\Report;
use App\Models\ReportStatusLog;

class ReportObserver
{
    public function updated(Report $report): void
    {
        if (! $report->wasChanged('status')) {
            return;
        }

        ReportStatusLog::create([
            'report_id' => $report->id,
            'from_status' => $report->getOriginal('status'),
            'to_status' => $report->status,
            'actor_id' => auth()->id(),
        ]);
    }
}
