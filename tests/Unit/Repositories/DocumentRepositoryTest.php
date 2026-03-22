<?php

namespace Tests\Unit\Repositories;

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
            'candidate_id' => \App\Models\Candidate::factory()->create()->id,
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
}