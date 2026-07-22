<?php

namespace Tests\Feature\Admin;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GlobalSearchControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guestIsRedirected(): void
    {
        $this->get(route('admin.search', ['q' => 'developer']))
            ->assertRedirect();
    }

    #[Test]
    public function returnsEmptyForShortQuery(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'admin')
            ->getJson(route('admin.search', ['q' => 'a']))
            ->assertOk()
            ->assertJson(['results' => []]);
    }

    #[Test]
    public function returnsEmptyForMissingQuery(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'admin')
            ->getJson(route('admin.search'))
            ->assertOk()
            ->assertJson(['results' => []]);
    }

    #[Test]
    public function searchesJobsAndReturnsLocalizedType(): void
    {
        $user = User::factory()->create();
        Job::factory()->create(['title' => 'Backend Developer']);

        $this->actingAs($user, 'admin')
            ->getJson(route('admin.search', ['q' => 'backend']))
            ->assertOk()
            ->assertJsonCount(1, 'results')
            ->assertJsonPath('results.0.type', __('admin.search.job'))
            ->assertJsonPath('results.0.label', fn($label) => str_contains($label, 'Backend Developer'));
    }

    #[Test]
    public function searchesCandidatesAndReturnsLocalizedType(): void
    {
        $user = User::factory()->create();
        $candidate = \App\Models\Candidate::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->actingAs($user, 'admin')
            ->getJson(route('admin.search', ['q' => 'john']))
            ->assertOk()
            ->assertJsonPath('results.0.type', __('admin.search.candidate'));
    }

    #[Test]
    public function searchesMultipleModelsAndReturnsAllResults(): void
    {
        $user = User::factory()->create();
        $batch = Batch::factory()->active()->create(['name' => 'Backend Batch']);
        Job::factory()->create(['title' => 'Backend Developer', 'batch_id' => $batch->id]);

        $this->actingAs($user, 'admin')
            ->getJson(route('admin.search', ['q' => 'backend']))
            ->assertOk()
            ->assertJsonCount(2, 'results');
    }

    #[Test]
    public function respectsLocaleSwitchForTypeLabels(): void
    {
        $user = User::factory()->create();
        Job::factory()->create(['title' => 'Backend Developer']);

        app()->setLocale('id');

        $this->actingAs($user, 'admin')
            ->getJson(route('admin.search', ['q' => 'backend']))
            ->assertOk()
            ->assertJsonPath('results.0.type', __('admin.search.job'));
    }
}
