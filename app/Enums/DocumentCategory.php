<?php

namespace App\Enums;

enum DocumentCategory: string
{
    case IDENTITY = 'IDENTITY';
    case EDUCATIONAL = 'EDUCATIONAL';
    case CERTIFICATION = 'CERTIFICATION';
    case MEDICAL = 'MEDICAL';
    case PORTFOLIO = 'PORTFOLIO';
    case OTHER = 'OTHER';

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
            self::IDENTITY => 'Dokumen Identitas',
            self::EDUCATIONAL => 'Dokumen Pendidikan',
            self::CERTIFICATION => 'Sertifikasi & Sertifikat',
            self::MEDICAL => 'Dokumen Kesehatan (MCU)',
            self::PORTFOLIO => 'Portfolio',
            self::OTHER => 'Dokumen Lainnya',
        };
    }

    public function getStoragePath(): string
    {
        return match ($this) {
            self::IDENTITY => 'candidates/documents/identity',
            self::EDUCATIONAL => 'candidates/documents/educational',
            self::CERTIFICATION => 'candidates/documents/certification',
            self::MEDICAL => 'candidates/documents/medical',
            self::PORTFOLIO => 'candidates/documents/portfolio',
            self::OTHER => 'candidates/documents/other',
        };
    }
}
