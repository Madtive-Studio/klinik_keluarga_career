<?php

namespace Database\Factories;

use App\Models\Apply;
use App\Models\Candidate;
use App\Models\Document;
use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ApplyFactory extends Factory
{
    protected $model = Apply::class;

    public function definition(): array
    {
        return [
            'uuid'         => (string) Str::uuid(),
            'cover_letter' => $this->faker->paragraph(),
            'status'       => 'IN REVIEW',
            'description'  => $this->faker->paragraph(),
            'created_at'   => now(),
            'updated_at'   => now(),
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Apply $apply) {
            if (!$apply->job_id) {
                $job = Job::factory()->create();
                $apply->job_id = $job->id;
            }
            if (!$apply->batch_id) {
                $apply->batch_id = Job::query()->findOrFail($apply->job_id)->batch_id;
            }
            if (!$apply->candidate_id) {
                $apply->candidate_id = Candidate::factory()->create([
                    'email_verified_at' => now(),
                ])->id;
            }
            if (!$apply->document_id) {
                $candidate = Candidate::query()->findOrFail($apply->candidate_id);
                $apply->document_id = Document::factory()->for($candidate)->cv()->create()->id;
            }
        });
    }

    public function forJobAndCandidate(Job $job, Candidate $candidate, Document $document): static
    {
        return $this->state(fn (array $attributes) => [
            'job_id'       => $job->id,
            'batch_id'     => $job->batch_id,
            'candidate_id' => $candidate->id,
            'document_id'  => $document->id,
        ]);
    }
}
