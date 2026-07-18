<?php

namespace Tests\Feature\Candidate;

use App\Enums\DocumentType;
use App\Models\Candidate;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class DocumentControllerTest extends TestCase
{
    use RefreshDatabase;

    private Candidate $candidate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->candidate = Candidate::factory()->create([
            'email_verified_at' => now(),
        ]);
    }

    // =========================================================
    // INDEX
    // =========================================================

    #[Test]
    public function indexReturnsDocumentPageForAuthenticatedCandidate(): void
    {
        $this->actingAs($this->candidate, 'candidate');

        $response = $this->get(route('candidate.my.documents.index'));

        $response->assertStatus(200);
        $response->assertViewIs('candidate.documents.index');
        $response->assertViewHas('candidate');
    }

    #[Test]
    public function indexRedirectsToLoginWhenUnauthenticated(): void
    {
        $response = $this->get(route('candidate.my.documents.index'));

        $response->assertRedirect();
    }

    #[Test]
    public function indexFiltersDocumentsByType(): void
    {
        $this->actingAs($this->candidate, 'candidate');

        Document::factory()->cv()->create(['candidate_id' => $this->candidate->id]);
        Document::factory()->mcu()->create(['candidate_id' => $this->candidate->id]);

        $response = $this->get(route('candidate.my.documents.index', ['type' => DocumentType::CV->value]));

        $response->assertStatus(200);
        $response->assertViewHas('activeType', DocumentType::CV);
        $documents = $response->viewData('candidate')->documents;
        $this->assertCount(1, $documents);
        $this->assertSame(DocumentType::CV->value, $documents->first()->type->value);
    }

    #[Test]
    public function indexReturnsNotFoundForInvalidDocumentType(): void
    {
        $this->actingAs($this->candidate, 'candidate');

        $response = $this->get(route('candidate.my.documents.index', ['type' => 'INVALID']));

        $response->assertNotFound();
    }

    // =========================================================
    // CREATE (legacy redirect)
    // =========================================================

    #[Test]
    public function createRedirectsToDocumentsIndex(): void
    {
        $this->actingAs($this->candidate, 'candidate');

        $response = $this->get(route('candidate.my.documents.create'));

        $response->assertRedirect(route('candidate.my.documents.index'));
    }

    // =========================================================
    // STORE — Happy Path
    // =========================================================

    #[Test]
    public function storeSuccessfullyUploadsPdfDocument(): void
    {
        Storage::fake('public');
        $this->actingAs($this->candidate, 'candidate');

        $file = UploadedFile::fake()->create('cv_test.pdf', 500, 'application/pdf');

        $response = $this->post(route('candidate.my.documents.store'), [
            'file' => $file,
            'type' => DocumentType::CV->value,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('documents', [
            'candidate_id' => $this->candidate->id,
            'type'         => DocumentType::CV->value,
        ]);
    }

    #[Test]
    public function storeSuccessfullyUploadsImageDocument(): void
    {
        Storage::fake('public');
        $this->actingAs($this->candidate, 'candidate');

        $file = UploadedFile::fake()->image('mcu_photo.jpg');

        $response = $this->post(route('candidate.my.documents.store'), [
            'file' => $file,
            'type' => DocumentType::MCU->value,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('documents', [
            'candidate_id' => $this->candidate->id,
            'type'         => DocumentType::MCU->value,
        ]);
    }

    #[Test]
    public function storeReturnsJsonResponseForAjaxRequest(): void
    {
        Storage::fake('public');
        $this->actingAs($this->candidate, 'candidate');

        $file = UploadedFile::fake()->create('str_document.pdf', 500, 'application/pdf');

        $response = $this->postJson(route('candidate.my.documents.store'), [
            'file' => $file,
            'type' => DocumentType::STR->value,
        ]);

        $response->assertOk();
        $response->assertJson([
            'message' => __('messages.document.upload_success'),
        ]);
        $this->assertDatabaseHas('documents', [
            'candidate_id' => $this->candidate->id,
            'type' => DocumentType::STR->value,
        ]);
    }

    #[Test]
    public function storeReturnsJsonValidationErrorsForAjaxRequest(): void
    {
        $this->actingAs($this->candidate, 'candidate');

        $response = $this->postJson(route('candidate.my.documents.store'), [
            'type' => DocumentType::CV->value,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['file']);
    }

    // =========================================================
    // STORE — Validation Failures
    // =========================================================

    #[Test]
    public function storeFailsWhenFileIsMissing(): void
    {
        $this->actingAs($this->candidate, 'candidate');

        $response = $this->post(route('candidate.my.documents.store'), [
            'type' => DocumentType::CV->value,
        ]);

        $response->assertSessionHasErrors(['file']);
        $this->assertDatabaseCount('documents', 0);
    }

    #[Test]
    public function storeFailsWhenTypeIsMissing(): void
    {
        Storage::fake('public');
        $this->actingAs($this->candidate, 'candidate');

        $file = UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf');

        $response = $this->post(route('candidate.my.documents.store'), [
            'file' => $file,
        ]);

        $response->assertSessionHasErrors(['type']);
        $this->assertDatabaseCount('documents', 0);
    }

    #[Test]
    public function storeFailsWhenFileExtensionIsNotAllowed(): void
    {
        $this->actingAs($this->candidate, 'candidate');

        $file = UploadedFile::fake()->create('malware.exe', 100, 'application/octet-stream');

        $response = $this->post(route('candidate.my.documents.store'), [
            'file' => $file,
            'type' => DocumentType::CV->value,
        ]);

        $response->assertSessionHasErrors(['file']);
        $this->assertDatabaseCount('documents', 0);
    }

    #[Test]
    public function storeFailsWhenFileSizeExceedsLimit(): void
    {
        $this->actingAs($this->candidate, 'candidate');

        // 20481 KB > 20480 KB (max defined in DocumentRequest)
        $file = UploadedFile::fake()->create('heavy_file.pdf', 20481, 'application/pdf');

        $response = $this->post(route('candidate.my.documents.store'), [
            'file' => $file,
            'type' => DocumentType::CV->value,
        ]);

        $response->assertSessionHasErrors(['file']);
        $this->assertDatabaseCount('documents', 0);
    }

    #[Test]
    public function storeRedirectsToLoginWhenUnauthenticated(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf');

        $response = $this->post(route('candidate.my.documents.store'), [
            'file' => $file,
            'type' => DocumentType::CV->value,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('documents', 0);
    }

    // =========================================================
    // DESTROY — Happy Path
    // =========================================================

    #[Test]
    public function destroySuccessfullyDeletesOwnDocument(): void
    {
        Storage::fake('public');
        $this->actingAs($this->candidate, 'candidate');

        $document = Document::factory()->create([
            'candidate_id' => $this->candidate->id,
        ]);

        $response = $this->delete(route('candidate.my.documents.destroy', $document->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('documents', ['id' => $document->id]);
    }

    #[Test]
    public function destroyRedirectsToLoginWhenUnauthenticated(): void
    {
        $document = Document::factory()->create([
            'candidate_id' => $this->candidate->id,
        ]);

        $response = $this->delete(route('candidate.my.documents.destroy', $document->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('documents', ['id' => $document->id]);
    }
}