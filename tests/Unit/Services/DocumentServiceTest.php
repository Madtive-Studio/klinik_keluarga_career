<?php

namespace Tests\Unit\Services;

use App\Enums\DocumentType;
use App\Repositories\CandidateRepository;
use App\Repositories\DocumentRepository;
use App\Services\DocumentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Unit Test for DocumentService.
 *
 * No database interaction here — all repositories are mocked
 * so tests run fast and in complete isolation.
 */
class DocumentServiceTest extends TestCase
{
    private DocumentRepository|MockInterface $documentRepo;
    private CandidateRepository|MockInterface $candidateRepo;
    private DocumentService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->documentRepo  = Mockery::mock(DocumentRepository::class);
        $this->candidateRepo = Mockery::mock(CandidateRepository::class);

        $this->service = new DocumentService(
            $this->documentRepo,
            $this->candidateRepo
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // =========================================================
    // store()
    // =========================================================

    #[Test]
    public function storeReturnsTrueOnSuccessfulUpload(): void
    {
        Storage::fake('public');
        Auth::shouldReceive('guard')->with('candidate')->andReturnSelf();
        Auth::shouldReceive('id')->andReturn(1);

        $fakeFile    = UploadedFile::fake()->create('cv.pdf', 200, 'application/pdf');
        $fakeRequest = new class($fakeFile) {
            public string $type = 'CV';
            private $file;

            public function __construct($file)
            {
                $this->file = $file;
            }

            public function file(string $key)
            {
                return $this->file;
            }
        };

        $this->documentRepo
            ->shouldReceive('create')
            ->once()
            ->andReturn(true);

        $result = $this->service->store($fakeRequest);

        $this->assertTrue($result);
    }

    #[Test]
    public function storeThrowsDomainExceptionWhenFileIsNull(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('File invalid');

        $fakeRequest = new class {
            public string $type = 'CV';

            public function file(string $key)
            {
                return null;
            }
        };

        $this->service->store($fakeRequest);
    }

    // =========================================================
    // delete()
    // =========================================================

    #[Test]
    public function deleteReturnsTrueWhenDocumentExists(): void
    {
        $this->documentRepo
            ->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $result = $this->service->delete(1);

        $this->assertTrue($result);
    }

    #[Test]
    public function deleteThrowsDomainExceptionWhenDocumentNotFound(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Dokumen tidak ditemukan');

        $this->documentRepo
            ->shouldReceive('delete')
            ->once()
            ->with(999)
            ->andReturn(false);

        $this->service->delete(999);
    }

    // =========================================================
    // getCandidateDocumentsPaginated()
    // =========================================================

    #[Test]
    public function getCandidateDocumentsPaginatedSetsDocumentsCountFromPaginator(): void
    {
        $paginator = new LengthAwarePaginator(
            items:       collect(array_fill(0, 7, new \stdClass())),
            total:       7,
            perPage:     5,
            currentPage: 1
        );

        $fakeCandidate            = new \stdClass();
        $fakeCandidate->documents = $paginator;

        $this->candidateRepo
            ->shouldReceive('getWithDocumentsPaginated')
            ->once()
            ->with(1, 5, '*')
            ->andReturn($fakeCandidate);

        $result = $this->service->getCandidateDocumentsPaginated(1, 5, '*');

        $this->assertEquals(7, $result->documents_count);
    }

    #[Test]
    public function getCandidateDocumentsPaginatedSetsDocumentsCountToZeroWhenNoDocuments(): void
    {
        $emptyPaginator = new LengthAwarePaginator(
            items:       collect(),
            total:       0,
            perPage:     5,
            currentPage: 1
        );

        $fakeCandidate            = new \stdClass();
        $fakeCandidate->documents = $emptyPaginator;

        $this->candidateRepo
            ->shouldReceive('getWithDocumentsPaginated')
            ->once()
            ->with(2, 5, '*')
            ->andReturn($fakeCandidate);

        $result = $this->service->getCandidateDocumentsPaginated(2, 5, '*');

        $this->assertSame(0, $result->documents_count);
    }

    // =========================================================
    // uploadDocument()
    // =========================================================

    #[Test]
    public function uploadDocumentStoresFileUnderCorrectTypePath(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('mcu_result.pdf', 100, 'application/pdf');
        $type = DocumentType::MCU;

        $storedPath = $this->service->uploadDocument($file, $type);

        $this->assertStringContainsString('candidates/documents/mcu', $storedPath);
        Storage::disk('public')->assertExists($storedPath);
    }

    #[Test]
    public function uploadDocumentStoresCvFileUnderCvPath(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('my_cv.pdf', 100, 'application/pdf');
        $type = DocumentType::CV;

        $storedPath = $this->service->uploadDocument($file, $type);

        $this->assertStringContainsString('candidates/documents/cv', $storedPath);
        Storage::disk('public')->assertExists($storedPath);
    }
}