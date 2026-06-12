<?php

namespace App\Enums;

enum IdentityType: string
{
    case KTP = 'KTP';
    case PASSPORT = 'PASSPORT';
    case DRIVING_LICENSE = 'DRIVING_LICENSE';
    case NATIONAL_ID = 'NATIONAL_ID';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getWithLabels(): array
    {
        $result = [];
        foreach (self::cases() as $case) {
            $result[$case->value] = $case->getLabel();
        }
        return $result;
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::KTP => 'KTP (Kartu Tanda Penduduk)',
            self::PASSPORT => 'Passport',
            self::DRIVING_LICENSE => 'Surat Ijin Mengemudi (SIM)',
            self::NATIONAL_ID => 'Nomor Induk Kependudukan (NIK)',
        };
    }
}
