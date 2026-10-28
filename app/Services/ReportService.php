<?php

namespace App\Services;

use App\Enums\ReferralParty;
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

        $this->transitionTo($report, $nextStatus, 'ditinjau lagi');

        $report->review_note = $note;
        $report->reviewed_by = $reviewer->id;
        $report->reviewed_at = now();
        $report->save();

        return $report;
    }

    public function resubmit(Report $report): Report
    {
        $this->transitionTo($report, ReportStatus::Baru, 'dikirim ulang');

        $report->save();

        return $report;
    }

    public function refer(
        Report $report,
        ReferralParty $partyType,
        string $partyName,
        ?string $note,
        User $coordinator
    ): Report {
        $this->transitionTo($report, ReportStatus::Diteruskan, 'diteruskan');

        $report->referral_party_type = $partyType;
        $report->referral_party_name = $partyName;
        $report->referral_note = $note;
        $report->referred_by = $coordinator->id;
        $report->referred_at = now();
        $report->save();

        return $report;
    }

    public function close(Report $report, ?string $note, User $coordinator): Report
    {
        $this->transitionTo($report, ReportStatus::Selesai, 'ditutup');

        $report->closure_note = $note;
        $report->closed_by = $coordinator->id;
        $report->closed_at = now();
        $report->save();

        return $report;
    }

    private function transitionTo(
        Report $report,
        ReportStatus $nextStatus,
        string $action
    ): void {
        if (! $this->canTransition($report->status, $nextStatus)) {
            throw new DomainException(
                'Laporan ini tidak bisa ' . $action . '. Status saat ini: '
                . $report->status->label() . '.'
            );
        }

        $report->status = $nextStatus;
    }

    private function canTransition(ReportStatus $from, ReportStatus $to): bool
    {
        return match ($from) {
            ReportStatus::Baru => in_array($to, [
                ReportStatus::Ditinjau,
                ReportStatus::PerluDiteruskan,
                ReportStatus::PerluPerbaikan,
            ], true),
            ReportStatus::PerluPerbaikan => $to === ReportStatus::Baru,
            ReportStatus::PerluDiteruskan => $to === ReportStatus::Diteruskan,
            ReportStatus::Ditinjau => $to === ReportStatus::Selesai,
            ReportStatus::Diteruskan => $to === ReportStatus::Selesai,
            default => false,
        };
    }
}
