<?php

namespace App\Enums;

enum ReviewDecision: string
{
    case TidakPerluDiteruskan = 'tidak_perlu_diteruskan';
    case PerluDiteruskan = 'perlu_diteruskan';
    case PerluPerbaikan = 'perlu_perbaikan';

    public function label(): string
    {
        return match ($this) {
            self::TidakPerluDiteruskan => 'Tidak perlu diteruskan',
            self::PerluDiteruskan => 'Perlu diteruskan',
            self::PerluPerbaikan => 'Perlu perbaikan',
        };
    }

    public function status(): ReportStatus
    {
        return match ($this) {
            self::TidakPerluDiteruskan => ReportStatus::Ditinjau,
            self::PerluDiteruskan => ReportStatus::PerluDiteruskan,
            self::PerluPerbaikan => ReportStatus::PerluPerbaikan,
        };
    }
}
