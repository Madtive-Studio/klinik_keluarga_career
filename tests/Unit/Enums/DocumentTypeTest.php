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
    public function getPathReturnsCvStoragePath(): void
    {
        $this->assertEquals('candidates/documents/cv', DocumentType::CV->getPath());
    }

    #[Test]
    public function getPathReturnsMcuStoragePath(): void
    {
        $this->assertEquals('candidates/documents/mcu', DocumentType::MCU->getPath());
    }

    #[Test]
    public function getPathReturnsOthersStoragePath(): void
    {
        $this->assertEquals('candidates/documents/others', DocumentType::OTHERS->getPath());
    }

    // =========================================================
    // getLabel()
    // =========================================================

    #[Test]
    public function getLabelReturnsCvLabel(): void
    {
        $this->assertEquals('Curriculum Vitae', DocumentType::CV->getLabel());
    }

    #[Test]
    public function getLabelReturnsMcuLabel(): void
    {
        $this->assertEquals('Medical Checkup Unit', DocumentType::MCU->getLabel());
    }

    #[Test]
    public function getLabelReturnsOthersLabel(): void
    {
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
        $this->assertContains('MCU', $values);
        $this->assertContains('OTHERS', $values);
        $this->assertCount(3, $values);
    }

    #[Test]
    public function getWithLabelsReturnsMapOfValueToLabel(): void
    {
        $labels = DocumentType::getWithLabels();

        $this->assertArrayHasKey('CV', $labels);
        $this->assertArrayHasKey('MCU', $labels);
        $this->assertArrayHasKey('OTHERS', $labels);
        $this->assertEquals('Curriculum Vitae', $labels['CV']);
    }
}