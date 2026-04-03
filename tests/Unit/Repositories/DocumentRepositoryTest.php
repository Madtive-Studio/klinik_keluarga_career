<?php

namespace Tests\Unit\Repositories;

use App\Enums\DocumentType;
use App\Models\Candidate;
use App\Models\Document;
use App\Repositories\DocumentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Unit Test for DocumentRepository (Thick Repository Pattern).
 * 
 * This repository contains both DB operations AND business logic.
 * Tests cover both layers since they're tightly coupled here.
 */
class DocumentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private DocumentRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new DocumentRepository();
    }

    // =========================================================
    // DB Operations Tests
    // =========================================================

    #[Test]
    public function createReturnsTrueAndPersistsRecord(): void
    {
        $data = [
            'name'         => 'cv_john.pdf',
            'file'         => 'candidates/documents/cv/cv_john.pdf',
            'type'         => 'CV',
            'candidate_id' => Candidate::factory()->create()->id,
            'created_at'   => now(),
            'updated_at'   => now(),
        ];

        $result = $this->repository->create($data);

        $this->assertTrue($result);
        $this->assertDatabaseHas('documents', [
            'name' => 'cv_john.pdf',
            'type' => 'CV',
        ]);
    }

    // =========================================================
    // delete()
    // =========================================================

    #[Test]
    public function deleteReturnsTrueAndRemovesRecord(): void
    {
        $document = Document::factory()->create();

        $result = $this->repository->delete($document->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('documents', ['id' => $document->id]);
    }

    #[Test]
    public function deleteReturnsFalseWhenDocumentDoesNotExist(): void
    {
        $nonExistentId = 99999;

        $result = $this->repository->delete($nonExistentId);

        $this->assertFalse($result);
    }

    // =========================================================
    // createFromUpload()
    // =========================================================

    #[Test]
    public function createFromUploadReturnsDocumentModel(): void
    {
        $candidateId = Candidate::factory()->create()->id;
        $data = [
            'name'         => 'upload.pdf',
            'file'         => 'candidates/documents/cv/x.pdf',
            'type'         => 'CV',
            'candidate_id' => $candidateId,
            'created_at'   => now(),
            'updated_at'   => now(),
        ];

        $document = $this->repository->createFromUpload($data);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertSame('upload.pdf', $document->name);
        $this->assertDatabaseHas('documents', ['id' => $document->id]);
    }

    // =========================================================
    // findCVsByCandidate()
    // =========================================================

    #[Test]
    public function findCVsByCandidateReturnsOnlyCvDocuments(): void
    {
        $candidate = Candidate::factory()->create();
        Document::factory()->for($candidate)->cv()->create(['name' => 'a.pdf']);
        Document::factory()->for($candidate)->mcu()->create(['name' => 'b.pdf']);

        $cvs = $this->repository->findCVsByCandidate($candidate->id);

        $this->assertCount(1, $cvs);
        $this->assertSame('a.pdf', $cvs->first()->name);
    }

    // =========================================================
    // Business Logic Tests (moved from Service)
    // =========================================================

    #[Test]
    public function uploadDocumentStoresFileUnderCorrectTypePath(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('mcu_result.pdf', 100, 'application/pdf');
        $path = $this->repository->uploadDocument($file, DocumentType::MCU);

        $this->assertStringContainsString('candidates/documents/mcu', $path);
        Storage::disk('public')->assertExists($path);
    }

    #[Test]
    public function storeReturnsTrueOnSuccessfulUpload(): void
    {
        Storage::fake('public');
        Auth::shouldReceive('guard')->with('candidate')->andReturnSelf();
        Auth::shouldReceive('id')->andReturn(1);

        $candidate = Candidate::factory()->create(['id' => 1]);
        $fakeFile = UploadedFile::fake()->create('cv.pdf', 200, 'application/pdf');
        $fakeRequest = new class($fakeFile) {
            private $file;
            public function __construct($file) {
                $this->file = $file;
            }
            public function file($key) {
                return $this->file;
            }
            public function __get($name) {
                if ($name === 'type') return 'CV';
                return null;
            }
        };

        $result = $this->repository->store($fakeRequest);

        $this->assertTrue($result);
        $this->assertDatabaseHas('documents', [
            'name' => 'cv.pdf',
            'type' => 'CV',
            'candidate_id' => 1,
        ]);
    }

    #[Test]
    public function storeThrowsDomainExceptionWhenFileIsNull(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('File invalid');

        $fakeRequest = new class {
            public function file($key) {
                return null;
            }
            public function __get($name) {
                if ($name === 'type') return 'CV';
                return null;
            }
        };

        $this->repository->store($fakeRequest);
    }
}