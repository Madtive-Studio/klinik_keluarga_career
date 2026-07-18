<?php

namespace App\Enums;

enum ScoreRecommendation: string
{
    case SHORTLIST = 'SHORTLIST';
    case REVIEW = 'REVIEW';
    case REJECT = 'REJECT';

    public function label(): string
    {
        return match ($this) {
            self::SHORTLIST => 'Direkomendasikan',
            self::REVIEW => 'Perlu Ditinjau',
            self::REJECT => 'Kurang Sesuai',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::SHORTLIST => 'bg-success',
            self::REVIEW => 'bg-warning',
            self::REJECT => 'bg-danger',
        };
    }
}
