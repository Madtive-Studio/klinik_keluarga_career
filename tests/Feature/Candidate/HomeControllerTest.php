<?php

namespace Tests\Feature\Candidate;

use App\Models\Batch;
use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function homeDisplaysPageWithExpectedData(): void
    {
        $batch = Batch::factory()->active()->create();
        Job::factory()->count(3)->create(['batch_id' => $batch->id]);

        $response = $this->get(route('candidate.home'));

        $response->assertStatus(200);
        $response->assertViewIs('candidate.home');
        $response->assertViewHas('jobsByType');
        $response->assertViewHas('jobTypes');
        $response->assertViewHas('categories');
        $response->assertViewHas('formattedBatch');
    }

    #[Test]
    public function jobsByTypeReturnsRenderedHtmlViaAjax(): void
    {
        $batch = Batch::factory()->active()->create();
        Job::factory()->count(2)->internship()->create(['batch_id' => $batch->id]);

        $response = $this->getJson(route('candidate.home.jobs-by-type', [
            'job_type' => 'Internship',
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure(['html']);
    }
}
