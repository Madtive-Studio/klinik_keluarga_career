<?php

declare(strict_types=1);

namespace Tests\Feature\Candidate;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Candidate;
use App\Models\Document;
use Illuminate\Http\UploadedFile;

class DocumentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    // Thread-based testing

    #[Test]
    public function threadBasedKolaborasiControllerServiceRepositoryDatabase(): void
    {
        $candidate = Candidate::factory()->create();
        $file = UploadedFile::fake()->create('cv.pdf', 100);

        $response = $this->actingAs($candidate, 'candidate')
            ->post(route('candidate.my.documents.store'), [
                'file' => $file,
                'type' => 'CV'
            ]);

        $this->assertTrue($response->isRedirect());
        $response->assertSessionHas('success', 'Berhasil upload dokumen');

        $this->assertTrue(
            Document::where('type', 'CV')
                ->where('candidate_id', $candidate->id)
                ->where('name', 'cv.pdf')
                ->exists()
        );
    }

    // Use-based testing
    #[Test]
    public function useBasedUploadMultipleDocumentsSequentially(): void
    {
        $candidate = Candidate::factory()->create();
        $this->actingAs($candidate, 'candidate');

        // Pertama: upload CV (kelas mandiri)
        $this->post(route('candidate.my.documents.store'), [
            'file' => UploadedFile::fake()->create('cv.pdf', 100),
            'type' => 'CV'
        ]);

        // Kedua: upload ijazah (tergantung CV sudah ada)
        $this->post(route('candidate.my.documents.store'), [
            'file' => UploadedFile::fake()->create('ijazah.pdf', 100),
            'type' => 'IJAZAH'
        ]);

        $this->assertSame(2, Document::where('candidate_id', $candidate->id)->count());
    }

    // ==================== SCENARIO-BASED TESTING ====================
    
    #[Test]
    // Scenario 1: User mendaftar dan upload dokumen persyaratan (PDF hal 14-15)
    public function scenarioUserRegisterAndUploadRequiredDocuments(): void
    {
        // 1. User login sebagai candidate
        $user = Candidate::factory()->create();
        $this->actingAs($user, 'candidate');

        // 2. User upload CV
        $this->post(route('candidate.my.documents.store'), [
            'file' => UploadedFile::fake()->create('cv_ahmad.pdf', 100),
            'type' => 'CV'
        ]);

        // 3. User upload ijazah
        $this->post(route('candidate.my.documents.store'), [
            'file' => UploadedFile::fake()->create('ijazah_s1.pdf', 200),
            'type' => 'MCU'
        ]);

        // 4. User upload transkrip
        $this->post(route('candidate.my.documents.store'), [
            'file' => UploadedFile::fake()->create('transkrip.pdf', 150),
            'type' => 'MCU'
        ]);

        // 5. User melihat daftar dokumen yang sudah diupload
        $response = $this->get(route('candidate.my.documents.index'));

        $this->assertStringContainsString('cv_ahmad.pdf', $response->getContent());
        $this->assertStringContainsString('ijazah_s1.pdf', $response->getContent());
        $this->assertStringContainsString('transkrip.pdf', $response->getContent());

        // 6. Verifikasi semua tersimpan di database
        $this->assertSame(3, Document::where('candidate_id', $user->id)->count());
    }

    
    #[Test]
    // Scenario 2: User upload file dengan tipe tidak valid
    public function scenarioUserUploadInvalidFileType(): void
    {
        $user = Candidate::factory()->create();
        $this->actingAs($user, 'candidate');

        $response = $this->post(route('candidate.my.documents.store'), [
            'file' => UploadedFile::fake()->create('file.exe', 100),
            'type' => 'CV'
        ]);

        // System rejects dangerous files - validation error
        $response->assertInvalid(['file']);

        // File not saved to database
        $this->assertFalse(
            Document::where('name', 'file.exe')
                ->where('candidate_id', $user->id)
                ->exists()
        );
    }
}
