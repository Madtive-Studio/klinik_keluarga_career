<?php

namespace App\Enums;

enum DocumentStatus: string
{
    case PENDING = 'PENDING';
    case VERIFIED = 'VERIFIED';
    case REJECTED = 'REJECTED';
    case UNDER_REVIEW = 'UNDER_REVIEW';

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
            self::PENDING => 'Menunggu Review',
            self::VERIFIED => 'Terverifikasi',
            self::REJECTED => 'Ditolak',
            self::UNDER_REVIEW => 'Sedang Direview',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::VERIFIED => 'success',
            self::REJECTED => 'danger',
            self::UNDER_REVIEW => 'info',
        };
    }

    public function getBadgeClass(): string
    {
        return 'badge-' . $this->getColor();
    }
}
