<?php

namespace App\Enums;

enum DocumentType: string
{
    case CV = 'CV';
    case IJAZAH = 'IJAZAH';
    case STR = 'STR';
    case SIP = 'SIP';
    case CERTIFICATE = 'CERTIFICATE';
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
            self::IJAZAH => 'candidates/documents/ijazah',
            self::STR => 'candidates/documents/str',
            self::SIP => 'candidates/documents/sip',
            self::CERTIFICATE => 'candidates/documents/certificate',
            self::MCU => 'candidates/documents/mcu',
            self::OTHERS => 'candidates/documents/others',
        };
    }

    public function getLabel(): string
    {
        return __('enums.document_type.' . $this->value);
    }

    public function getBadgeClass(): string
    {
        return match($this) {
            self::CV => 'bg-primary',
            self::IJAZAH => 'bg-info',
            self::STR => 'bg-success',
            self::SIP => 'bg-success',
            self::CERTIFICATE => 'bg-warning',
            self::MCU => 'bg-danger',
            self::OTHERS => 'bg-secondary',
        };
    }
}