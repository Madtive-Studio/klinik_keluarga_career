<?php

namespace Tests\Unit\Repositories;

use App\Models\Apply;
use App\Models\Candidate;
use App\Models\Document;
use App\Models\Job;
use App\Repositories\ApplicationRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApplicationRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ApplicationRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(ApplicationRepository::class);
    }

    #[Test]
    public function findByJobBatchAndCandidateReturnsApplyWhenExists(): void
    {
        $candidate = Candidate::factory()->create();
        $job = Job::factory()->create();
        $document = Document::factory()->for($candidate)->cv()->create();
        $apply = Apply::factory()->forJobAndCandidate($job, $candidate, $document)->create();

        $result = $this->repository->findByJobBatchAndCandidate($job->id, $job->batch_id, $candidate->id);

        $this->assertNotNull($result);
        $this->assertSame($apply->id, $result->id);
    }

    #[Test]
    public function findByJobBatchAndCandidateReturnsNullWhenMissing(): void
    {
        $job = Job::factory()->create();

        $this->assertNull(
            $this->repository->findByJobBatchAndCandidate($job->id, $job->batch_id, 999_999)
        );
    }

    #[Test]
    public function findApplicationByJobUuidAndCandidateReturnsApplyWithRelations(): void
    {
        $candidate = Candidate::factory()->create();
        $job = Job::factory()->create();
        $document = Document::factory()->for($candidate)->cv()->create();
        Apply::factory()->forJobAndCandidate($job, $candidate, $document)->create();

        $result = $this->repository->findApplicationByJobUuidAndCandidate($job->uuid, $candidate->id);

        $this->assertNotNull($result);
        $this->assertTrue($result->relationLoaded('job'));
        $this->assertTrue($result->relationLoaded('candidate'));
        $this->assertTrue($result->relationLoaded('batch'));
        $this->assertTrue($result->job->relationLoaded('category'));
    }

    #[Test]
    public function createPersistsApply(): void
    {
        $candidate = Candidate::factory()->create();
        $job = Job::factory()->create();
        $document = Document::factory()->for($candidate)->cv()->create();

        $data = [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'candidate_id' => $candidate->id,
            'job_id' => $job->id,
            'batch_id' => $job->batch_id,
            'document_id' => $document->id,
            'cover_letter' => 'Test',
            'status' => 'IN REVIEW',
            'description' => 'Test desc',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $apply = $this->repository->create($data);

        $this->assertInstanceOf(Apply::class, $apply);
        $this->assertDatabaseHas('applies', ['uuid' => $data['uuid'], 'job_id' => $job->id]);
    }

    #[Test]
    public function getApplicationsByCandidatePaginatedFiltersByStatusAndCurrentYear(): void
    {
        $candidate = Candidate::factory()->create();
        $document = Document::factory()->for($candidate)->cv()->create();
        $jobReview = Job::factory()->create();
        $jobHired = Job::factory()->create(['batch_id' => $jobReview->batch_id]);

        Apply::factory()->forJobAndCandidate($jobReview, $candidate, $document)->create([
            'status' => 'IN REVIEW',
            'created_at' => now(),
        ]);
        Apply::factory()->forJobAndCandidate($jobHired, $candidate, $document)->create([
            'status' => 'HIRED',
            'created_at' => now(),
        ]);

        $filters = [
            'status' => 'IN REVIEW',
            'sortedBy' => 'DESC',
        ];

        $paginator = $this->repository->getApplicationsByCandidatePaginated($candidate->id, 10, $filters);

        $this->assertSame(1, $paginator->total());
        $this->assertSame('IN REVIEW', $paginator->first()->status);
    }

    #[Test]
    public function getApplicationsByCandidatePaginatedFormattedAddsApplyCount(): void
    {
        $candidate = Candidate::factory()->create();
        $document = Document::factory()->for($candidate)->cv()->create();
        $job = Job::factory()->create();

        Apply::factory()->forJobAndCandidate($job, $candidate, $document)->count(3)->create();

        $filters = [
            'status' => null,
            'sortedBy' => 'NEWEST',
        ];

        $result = $this->repository->getApplicationsByCandidatePaginatedFormatted($candidate->id, 10, $filters);

        $this->assertSame(3, $result->apply_count);
        $this->assertSame(3, $result->total());
    }

    #[Test]
    public function candidateHasAppliedReturnsTrueWhenApplyExists(): void
    {
        $candidate = Candidate::factory()->create();
        $job = Job::factory()->create();
        $document = Document::factory()->for($candidate)->cv()->create();
        Apply::factory()->forJobAndCandidate($job, $candidate, $document)->create();

        $this->assertTrue($this->repository->candidateHasApplied($candidate->id, $job));
    }

    #[Test]
    public function candidateHasAppliedReturnsFalseWhenNoApply(): void
    {
        $candidate = Candidate::factory()->create();
        $job = Job::factory()->create();

        $this->assertFalse($this->repository->candidateHasApplied($candidate->id, $job));
    }
}
