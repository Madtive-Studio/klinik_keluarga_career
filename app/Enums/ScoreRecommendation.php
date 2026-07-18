<?php

namespace App\Enums;

enum ScoreRecommendation: string
{
    case SHORTLIST = 'SHORTLIST';
    case REVIEW = 'REVIEW';
    case REJECT = 'REJECT';

    public function label(): string
    {
        return __('enums.score_recommendation.' . $this->value);
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
