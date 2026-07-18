<?php

namespace App\Enums;

enum DocumentType: string
{
    case CV = 'CV';
    case MCU = 'MCU';
    case OTHERS = 'OTHERS';

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

    public function getPath(): string
    {
        return match($this) {
            self::CV => 'candidates/documents/cv',
            self::MCU => 'candidates/documents/mcu',
            self::OTHERS => 'candidates/documents/others',
        };
    }

    public function getLabel(): string
    {
        return __('enums.document_type.' . $this->value);
    }
}