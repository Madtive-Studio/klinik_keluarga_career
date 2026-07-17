<?php

namespace App\Enums;

enum JobType: string
{
    // $this->faker->randomElement(['WFH/Remote', 'Partime/Freelancer', 'Fulltime/Onsite', 'Internship']),
    case WFH_REMOTE = 'WFH/Remote';
    case PARTIME_FREELANCER = 'Partime/Freelancer';
    case FULLTIME_ONSITE = 'Fulltime/Onsite';
    case INTERNSHIP = 'Internship';
    
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
            self::WFH_REMOTE => 'WFH / Remote',
            self::PARTIME_FREELANCER => 'Part-time / Freelance',
            self::FULLTIME_ONSITE => 'Full-time / Onsite',
            self::INTERNSHIP => 'Internship',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::WFH_REMOTE => 'bg-label-info',
            self::PARTIME_FREELANCER => 'bg-label-warning',
            self::FULLTIME_ONSITE => 'bg-label-primary',
            self::INTERNSHIP => 'bg-label-success',
        };
    }

    public static function tryBadge(?string $value): string
    {
        $type = self::tryFrom((string) $value);

        if (!$type) {
            return '<span class="badge bg-label-secondary">' . e($value ?: '-') . '</span>';
        }

        return '<span class="badge ' . $type->badgeClass() . '">' . e($type->getLabel()) . '</span>';
    }
}
