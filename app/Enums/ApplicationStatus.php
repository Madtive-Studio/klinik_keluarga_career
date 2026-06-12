<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case IN_REVIEW = 'IN REVIEW';
    case NOT_SUITABLE = 'NOT SUITABLE';
    case SHORTLISTED = 'SHORTLISTED';
    case HIRED = 'HIRED';

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
            self::IN_REVIEW => 'Sedang Dalam Review',
            self::NOT_SUITABLE => 'Tidak Sesuai',
            self::SHORTLISTED => 'Lolos Tahap Selanjutnya',
            self::HIRED => 'Diterima',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::IN_REVIEW => 'warning',
            self::NOT_SUITABLE => 'danger',
            self::SHORTLISTED => 'info',
            self::HIRED => 'success',
        };
    }

    public function getBadgeClass(): string
    {
        return 'badge-' . $this->getColor();
    }
}
