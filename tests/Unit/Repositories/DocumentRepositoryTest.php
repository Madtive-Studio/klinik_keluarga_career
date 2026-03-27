<?php

namespace Tests\Unit\Repositories;

use App\Models\Candidate;
use App\Models\Document;
use App\Repositories\DocumentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Unit Test for DocumentRepository.
 *
 * This layer is thin (no business logic), so we only verify
 * that the DB operations (create, delete) work correctly.
 * Uses RefreshDatabase because the purpose here IS to hit the DB.
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
    // create()
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
}