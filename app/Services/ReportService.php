<?php

namespace App\Services;

use App\Enums\ReportStatus;
use App\Enums\ReviewDecision;
use App\Models\Report;
use App\Models\User;
use DomainException;

class ReportService
{
    public function review(
        Report $report,
        ReviewDecision $decision,
        string $note,
        User $reviewer
    ): Report {
        $nextStatus = $decision->status();

        if (! $this->canTransition($report->status, $nextStatus)) {
            throw new DomainException(
                'Laporan ini tidak bisa ditinjau lagi. Status saat ini: '
                . $report->status->label() . '.'
            );
        }

        $report->status = $nextStatus;
        $report->review_note = $note;
        $report->reviewed_by = $reviewer->id;
        $report->reviewed_at = now();
        $report->save();

        return $report;
    }

    private function canTransition(ReportStatus $from, ReportStatus $to): bool
    {
        return match ($from) {
            ReportStatus::Baru => in_array($to, [
                ReportStatus::Ditinjau,
                ReportStatus::PerluDiteruskan,
                ReportStatus::PerluPerbaikan,
            ], true),
            default => false,
        };
    }
}
