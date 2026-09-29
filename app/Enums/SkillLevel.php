<?php

namespace App\Enums;

/**
 * @deprecated SkillLevel is deprecated as candidate_skills table only tracks skill names.
 */
enum SkillLevel: string
{
    case BASIC = 'basic';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED = 'advanced';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
