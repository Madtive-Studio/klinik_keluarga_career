<?php

namespace Tests\Unit\Repositories;

use App\Enums\DocumentType;
use App\Models\Candidate;
use App\Models\Document;
use App\Repositories\CandidateRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CandidateRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CandidateRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CandidateRepository();
    }

    #[Test]
    public function findReturnsCandidateWhenExists(): void
    {
        $candidate = Candidate::factory()->create();

        $result = $this->repository->find($candidate->id);

        $this->assertNotNull($result);
        $this->assertSame($candidate->id, $result->id);
    }

    #[Test]
    public function findReturnsNullWhenMissing(): void
    {
        $this->assertNull($this->repository->find(999_999));
    }

    #[Test]
    public function findWithDocumentsEagerLoadsDocuments(): void
    {
        $candidate = Candidate::factory()->create();
        Document::factory()->for($candidate)->cv()->create();

        $result = $this->repository->findWithDocuments($candidate->id);

        $this->assertNotNull($result);
        $this->assertTrue($result->relationLoaded('documents'));
        $this->assertCount(1, $result->documents);
    }

    #[Test]
    public function getWithDocumentsPaginatedThrowsWhenCandidateNotFound(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->getWithDocumentsPaginated(999_999, 5, '*');
    }

    #[Test]
    public function getWithDocumentsPaginatedReplacesDocumentsRelationWithPaginator(): void
    {
        $candidate = Candidate::factory()->create();
        Document::factory()->count(3)->for($candidate)->create();

        $result = $this->repository->getWithDocumentsPaginated($candidate->id, 2, '*');

        $this->assertSame($candidate->id, $result->id);
        $this->assertCount(2, $result->documents);
        $this->assertSame(3, $result->documents->total());
    }

    #[Test]
    public function getWithDocumentsPaginatedFiltersByDocumentTypeWhenNotWildcard(): void
    {
        $candidate = Candidate::factory()->create();
        Document::factory()->for($candidate)->cv()->create();
        Document::factory()->for($candidate)->mcu()->create();

        $result = $this->repository->getWithDocumentsPaginated($candidate->id, 10, DocumentType::CV->value);

        $this->assertCount(1, $result->documents);
        $this->assertSame(DocumentType::CV->value, $result->documents->first()->type->value);
    }
}
