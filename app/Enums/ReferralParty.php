<?php

namespace App\Enums;

enum ReferralParty: string
{
    case Sekolah = 'sekolah';
    case Sppg = 'sppg';
    case Faskes = 'faskes';
    case Lain = 'lain';

    public function label(): string
    {
        return match ($this) {
            self::Sekolah => 'Sekolah',
            self::Sppg => 'SPPG',
            self::Faskes => 'Fasilitas Kesehatan',
            self::Lain => 'Lainnya',
        };
    }
}
