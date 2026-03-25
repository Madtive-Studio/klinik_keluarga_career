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
            self::WFH_REMOTE => 'WFH/Remote',
            self::PARTIME_FREELANCER => 'Partime/Freelancer',
            self::FULLTIME_ONSITE => 'Fulltime/Onsite',
            self::INTERNSHIP => 'Internship',
        };
    }
}
