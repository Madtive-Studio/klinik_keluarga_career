<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Repositories\CandidateRepository;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Services\DocumentService;
use App\Repositories\DocumentRepository;
use App\Enums\DocumentType;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Candidate;
use App\Models\Document;

class BusinessLogicTest extends TestCase
{
    use RefreshDatabase;

    private DocumentService $service;
    private DocumentRepository $repository;
    private CandidateRepository $candidateRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = \Mockery::mock(DocumentRepository::class);
        $this->candidateRepository = \Mockery::mock(CandidateRepository::class);
        $this->service = new DocumentService($this->repository, $this->candidateRepository);
    }

    // ==================== CLASS TESTING ====================
    // Unit Testing - Uji polymorphism kelas (PDF hal 9)

    #[Test]
    public function documentTypePolymorphismDifferentPathsPerType(): void
    {
        $this->assertSame('candidates/documents/cv', DocumentType::CV->getPath());
        $this->assertSame('candidates/documents/mcu', DocumentType::MCU->getPath());
        $this->assertSame('candidates/documents/others', DocumentType::OTHERS->getPath());
    }

    #[Test]
    public function documentTypePolymorphismDifferentLabelsPerType(): void
    {
        $this->assertSame('Curriculum Vitae', DocumentType::CV->getLabel());
        $this->assertSame('Medical Checkup Unit', DocumentType::MCU->getLabel());
        $this->assertSame('Dokumen Lainnya', DocumentType::OTHERS->getLabel());
    }

    #[Test]
    public function documentTypeStringConversion(): void
    {
        $this->assertSame(DocumentType::CV, DocumentType::from('CV'));
        $this->assertNull(DocumentType::tryFrom('UNKNOWN'));
    }

    // ==================== CLASS TESTING - ENCAPSULATION ====================
    // Enkapsulasi - uji melalui method public (PDF hal 6)

    #[Test]
    public function uploadDocumentReturnsCorrectPath(): void
    {
        $file = UploadedFile::fake()->create('cv.pdf', 100);
        $type = DocumentType::CV;

        $path = $this->service->uploadDocument($file, $type);

        $this->assertStringContainsString('documents/cv/', $path);
        $this->assertStringEndsWith('.pdf', $path);
    }

    // ==================== FAULT-BASED TESTING ====================
    // Fault-based testing - logic errors validation (PDF hal 14)
    // NOTE: Exception tests & integration tests dipindahkan ke Feature tests
    // untuk keep unit tests pure dan terfokus hanya pada business logic
}
