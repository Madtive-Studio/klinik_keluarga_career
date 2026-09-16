<?php

namespace App\Enums;

enum DocumentType: string
{
    case CV = 'CV';
    case SURAT_LAMARAN = 'SURAT_LAMARAN';
    case STR = 'STR';
    case CERTIFICATE = 'CERTIFICATE';
    case IJAZAH = 'IJAZAH';
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
            self::SURAT_LAMARAN => 'candidates/documents/surat_lamaran',
            self::STR => 'candidates/documents/str',
            self::CERTIFICATE => 'candidates/documents/certificate',
            self::IJAZAH => 'candidates/documents/ijazah',
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
            self::SURAT_LAMARAN => 'bg-indigo',
            self::STR => 'bg-success',
            self::CERTIFICATE => 'bg-warning',
            self::IJAZAH => 'bg-info',
            self::OTHERS => 'bg-secondary',
        };
    }
}