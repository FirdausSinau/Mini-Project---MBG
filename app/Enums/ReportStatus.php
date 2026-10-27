<?php

namespace App\Enums;

enum ReportStatus: string
{
    case Baru = 'baru';
    case Ditinjau = 'ditinjau';
    case PerluPerbaikan = 'perlu_perbaikan';
    case PerluDiteruskan = 'perlu_diteruskan';
    case Diteruskan = 'diteruskan';
    case Selesai = 'selesai';

    public function label(): string
    {
        return match ($this) {
            self::Baru => 'Baru',
            self::Ditinjau => 'Sudah Ditinjau',
            self::PerluPerbaikan => 'Perlu Perbaikan',
            self::PerluDiteruskan => 'Perlu Diteruskan',
            self::Diteruskan => 'Diteruskan',
            self::Selesai => 'Selesai Dipantau',
        };
    }
}
