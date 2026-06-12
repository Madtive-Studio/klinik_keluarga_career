<?php

use App\Enums\DocumentType;
use App\Models\Candidate;
use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->candidate = Candidate::factory()->create([
        'email_verified_at' => now(),
    ]);
});

it('shows document page for authenticated candidate', function () {
    $this->actingAs($this->candidate, 'candidate');

    $response = $this->get(route('candidate.my.documents.index'));

    $response->assertOk()
        ->assertViewIs('candidate.documents.index')
        ->assertViewHas('candidate');
});

it('redirects guests on document index', function () {
    $response = $this->get(route('candidate.my.documents.index'));

    $response->assertRedirect();
});

it('shows upload form with document types', function () {
    $this->actingAs($this->candidate, 'candidate');

    $response = $this->get(route('candidate.my.documents.create'));

    $response->assertOk()
        ->assertViewIs('candidate.documents.create')
        ->assertViewHas('types');
});

it('uploads pdf document successfully', function () {
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
        'type' => DocumentType::CV->value,
    ]);
});

it('fails when file is missing', function () {
    $this->actingAs($this->candidate, 'candidate');

    $response = $this->post(route('candidate.my.documents.store'), [
        'type' => DocumentType::CV->value,
    ]);

    $response->assertSessionHasErrors(['file']);
    $this->assertDatabaseCount('documents', 0);
});

it('fails when file extension is not allowed', function () {
    $this->actingAs($this->candidate, 'candidate');

    $file = UploadedFile::fake()->create('malware.exe', 100, 'application/octet-stream');

    $response = $this->post(route('candidate.my.documents.store'), [
        'file' => $file,
        'type' => DocumentType::CV->value,
    ]);

    $response->assertSessionHasErrors(['file']);
});

it('deletes own document', function () {
    Storage::fake('public');
    $this->actingAs($this->candidate, 'candidate');

    $document = Document::factory()->create([
        'candidate_id' => $this->candidate->id,
    ]);

    $response = $this->delete(route('candidate.my.documents.destroy', $document->id));

    $response->assertRedirect();
    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('documents', ['id' => $document->id]);
});
