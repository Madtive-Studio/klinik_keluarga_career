<?php

namespace App\Enums;

enum EducationLevel: string
{
    case SMA = 'SMA';
    case D3 = 'D3';
    case D4 = 'D4';
    case S1 = 'S1';
    case S2 = 'S2';
    case S3 = 'S3';

    public function label(): string
    {
        return __('enums.education_level.' . $this->value);
    }

    public function rank(): int
    {
        return match ($this) {
            self::SMA => 1,
            self::D3 => 2,
            self::D4 => 3,
            self::S1 => 4,
            self::S2 => 5,
            self::S3 => 6,
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

    public static function labelOf(?string $level): string
    {
        if ($level === null) {
            return __('common.dash');
        }

        return self::tryFrom($level)?->label() ?? $level;
    }
}
