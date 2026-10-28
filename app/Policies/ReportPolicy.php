<?php

namespace App\Policies;

use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    public function view(User $user, Report $report): bool
    {
        return $user->id === $report->user_id || $user->isStaff();
    }

    public function update(User $user, Report $report): bool
    {
        return $user->id === $report->user_id
            && in_array($report->status, [
                ReportStatus::Baru,
                ReportStatus::PerluPerbaikan,
            ], true);
    }

    public function review(User $user, Report $report): bool
    {
        return $user->isReviewer();
    }

    public function resubmit(User $user, Report $report): bool
    {
        return $user->id === $report->user_id
            && $report->status === ReportStatus::PerluPerbaikan;
    }

    public function refer(User $user, Report $report): bool
    {
        return $user->isCoordinator()
            && $report->status === ReportStatus::PerluDiteruskan;
    }

    public function close(User $user, Report $report): bool
    {
        return $user->isCoordinator()
            && in_array($report->status, [
                ReportStatus::Ditinjau,
                ReportStatus::Diteruskan,
            ], true);
    }
}
