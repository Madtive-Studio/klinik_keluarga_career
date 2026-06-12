<?php

namespace App\Enums;

enum DocumentType: string
{
    case CV = 'CV';
    case MCU = 'MCU';
    case KTP = 'KTP';
    case IJAZAH = 'IJAZAH';
    case SERTIFIKAT = 'SERTIFIKAT';
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
            self::KTP => 'candidates/documents/identity',
            self::IJAZAH => 'candidates/documents/educational',
            self::SERTIFIKAT => 'candidates/documents/certification',
            self::OTHERS => 'candidates/documents/others',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::CV => 'Curriculum Vitae',
            self::MCU => 'Medical Checkup Unit (MCU)',
            self::KTP => 'KTP (Kartu Tanda Penduduk)',
            self::IJAZAH => 'Ijazah / Diploma',
            self::SERTIFIKAT => 'Sertifikat & Sertifikasi',
            self::OTHERS => 'Dokumen Lainnya',
        };
    }

    public function getCategory(): DocumentCategory
    {
        return match($this) {
            self::CV => DocumentCategory::PORTFOLIO,
            self::MCU => DocumentCategory::MEDICAL,
            self::KTP => DocumentCategory::IDENTITY,
            self::IJAZAH => DocumentCategory::EDUCATIONAL,
            self::SERTIFIKAT => DocumentCategory::CERTIFICATION,
            self::OTHERS => DocumentCategory::OTHER,
        };
    }
}