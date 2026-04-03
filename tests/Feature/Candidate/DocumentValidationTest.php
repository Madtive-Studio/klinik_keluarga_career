<?php

declare(strict_types=1);

namespace Tests\Feature\Candidate;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Candidate;
use App\Models\Document;
use Illuminate\Http\UploadedFile;

class DocumentValidationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function validationUserSeesErrorMessageWhenUploadWithoutFile(): void
    {
        $user = Candidate::factory()->create();

        $response = $this->actingAs($user, 'candidate')
            ->post(route('candidate.my.documents.store'), [
                'type' => 'CV'
                // no file
            ]);

        // Validation error dari FormRequest
        $response->assertInvalid(['file']);
    }

    #[Test]
    public function validationUserSeesSuccessMessageWhenUploadSuccessful(): void
    {
        $user = Candidate::factory()->create();

        $response = $this->actingAs($user, 'candidate')
            ->post(route('candidate.my.documents.store'), [
                'file' => UploadedFile::fake()->create('test.pdf', 100),
                'type' => 'CV'
            ]);

        $response->assertSessionHas('success', 'Berhasil upload dokumen');
    }

    #[Test]
    public function validationUserSeesListOfUploadedDocuments(): void
    {
        $user = Candidate::factory()->create();
        $this->actingAs($user, 'candidate');

        // Upload beberapa dokumen
        foreach (['cv' => 'CV', 'ijazah' => 'MCU', 'transkrip' => 'OTHERS'] as $name => $type) {
            $this->post(route('candidate.my.documents.store'), [
                'file' => UploadedFile::fake()->create($name . '.pdf', 100),
                'type' => $type
            ]);
        }

        $response = $this->get(route('candidate.my.documents.index'));

        $response->assertOk();
        $this->assertStringContainsString('cv.pdf', $response->getContent());
        $this->assertStringContainsString('ijazah.pdf', $response->getContent());
        $this->assertStringContainsString('transkrip.pdf', $response->getContent());
    }
}
