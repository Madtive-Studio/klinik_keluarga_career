<?php

namespace App\Enums;

enum EducationLevel: string
{
    case SMA = 'SMA';
    case D3 = 'D3';
    case S1 = 'S1';
    case S2 = 'S2';
    case S3 = 'S3';

    public function rank(): int
    {
        return match ($this) {
            self::SMA => 1,
            self::D3 => 2,
            self::S1 => 3,
            self::S2 => 4,
            self::S3 => 5,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function rankOf(?string $level): int
    {
        if ($level === null) {
            return 0;
        }

        return self::tryFrom($level)?->rank() ?? 0;
    }
}
