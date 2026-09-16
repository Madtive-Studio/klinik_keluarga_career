<?php

namespace Tests\Feature\Admin;

use App\Models\Apply;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\User;
use App\Notifications\ApplicationStatusUpdatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApplicantControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    #[Test]
    public function guestIsRedirectedToAdminLogin(): void
    {
        $response = $this->get(route('admin.applies.index'));
        $response->assertRedirect(route('admin.login'));
    }

    #[Test]
    public function indexDisplaysAppliesPage(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.applies.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.applies.index');
        $response->assertViewHas('statuses');
    }

    #[Test]
    public function datatablesReturnsJsonData(): void
    {
        Apply::factory()->count(2)->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->getJson(route('admin.applies.datatables'));

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    #[Test]
    public function showDisplaysApplicationDetail(): void
    {
        $apply = Apply::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.applies.show', $apply->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.applies.detail');
        $response->assertViewHas(['apply', 'statuses']);
    }

    #[Test]
    public function updateModifiesStatusAndSendsNotification(): void
    {
        Notification::fake();

        $candidate = Candidate::factory()->create();
        $job = Job::factory()->create();
        $apply = Apply::factory()->create([
            'candidate_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => 'IN REVIEW',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.applies.update', $apply->id), [
                'status' => 'SHORTLISTED',
            ]);

        $response->assertRedirect(route('admin.applies.index'));
        $this->assertDatabaseHas('applies', [
            'id' => $apply->id,
            'status' => 'SHORTLISTED',
        ]);

        Notification::assertSentTo($candidate, ApplicationStatusUpdatedNotification::class);
    }
}
