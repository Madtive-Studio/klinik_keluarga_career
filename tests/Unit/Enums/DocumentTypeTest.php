<?php

namespace Tests\Unit\Enums;

use App\Enums\DocumentType;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Unit Test for DocumentType enum.
 *
 * Enum is a pure value object — no DB, no mocking needed.
 * We verify that path and label contracts stay consistent,
 * so a future refactor doesn't silently break file storage paths.
 */
class DocumentTypeTest extends TestCase
{
    // =========================================================
    // getPath()
    // =========================================================

    #[Test]
    public function getPathReturnsExpectedStoragePaths(): void
    {
        $this->assertEquals('candidates/documents/cv', DocumentType::CV->getPath());
        $this->assertEquals('candidates/documents/surat_lamaran', DocumentType::SURAT_LAMARAN->getPath());
        $this->assertEquals('candidates/documents/str', DocumentType::STR->getPath());
        $this->assertEquals('candidates/documents/certificate', DocumentType::CERTIFICATE->getPath());
        $this->assertEquals('candidates/documents/ijazah', DocumentType::IJAZAH->getPath());
        $this->assertEquals('candidates/documents/others', DocumentType::OTHERS->getPath());
    }

    // =========================================================
    // getLabel()
    // =========================================================

    #[Test]
    public function getLabelReturnsExpectedLabels(): void
    {
        $this->assertEquals('Curriculum Vitae', DocumentType::CV->getLabel());
        $this->assertEquals('Surat Lamaran Pekerjaan', DocumentType::SURAT_LAMARAN->getLabel());
        $this->assertEquals('STR', DocumentType::STR->getLabel());
        $this->assertEquals('Sertifikat Kompetensi / Pelatihan', DocumentType::CERTIFICATE->getLabel());
        $this->assertEquals('Ijazah / Transkrip', DocumentType::IJAZAH->getLabel());
        $this->assertEquals('Dokumen Lainnya', DocumentType::OTHERS->getLabel());
    }

    // =========================================================
    // getValues() / getWithLabels()
    // =========================================================

    #[Test]
    public function getValuesReturnsAllEnumValues(): void
    {
        $values = DocumentType::getValues();

        $this->assertContains('CV', $values);
        $this->assertContains('SURAT_LAMARAN', $values);
        $this->assertContains('STR', $values);
        $this->assertContains('CERTIFICATE', $values);
        $this->assertContains('IJAZAH', $values);
        $this->assertContains('OTHERS', $values);
        $this->assertCount(6, $values);
    }

    #[Test]
    public function getWithLabelsReturnsMapOfValueToLabel(): void
    {
        $labels = DocumentType::getWithLabels();

        $this->assertArrayHasKey('CV', $labels);
        $this->assertArrayHasKey('SURAT_LAMARAN', $labels);
        $this->assertArrayHasKey('STR', $labels);
        $this->assertArrayHasKey('CERTIFICATE', $labels);
        $this->assertArrayHasKey('IJAZAH', $labels);
        $this->assertArrayHasKey('OTHERS', $labels);
        $this->assertEquals('Curriculum Vitae', $labels['CV']);
    }
}
